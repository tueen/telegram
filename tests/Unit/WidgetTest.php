<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Methods\EditMessageWidgetData;
use Tueen\Telegram\Methods\EditSharedWidgetData;
use Tueen\Telegram\Methods\SendSharedWidget;
use Tueen\Telegram\Methods\SendWidget;
use Tueen\Telegram\Routing\Attributes\OnWidgetQuery;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Chat;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\Widget;
use Tueen\Telegram\Types\WidgetQuery;
use Tueen\Telegram\Types\WidgetQueryType;
use Tueen\Telegram\Types\WidgetQueryTypeData;
use Tueen\Telegram\Types\WidgetQueryTypeSentTransaction;
use Tueen\Telegram\Types\WidgetQueryTypeSignData;
use Tueen\Telegram\Types\WidgetQueryWallet;

class TestWidgetController
{
    public ?string $handledAction = null;
    public ?string $handledId = null;

    #[OnWidgetQuery('action:{action}:{id}')]
    public function onWidgetAction(string $action, string $id): void
    {
        $this->handledAction = $action;
        $this->handledId = $id;
    }
}

class WidgetTest extends TestCase
{
    public function testWidgetInstantiationAndProperties(): void
    {
        $widget = new Widget([
            'url' => 'https://example.com/widget.html',
            'data' => 'initial_state_123',
            'title' => 'Sample Game Widget',
        ]);

        $this->assertSame('https://example.com/widget.html', $widget->url);
        $this->assertSame('initial_state_123', $widget->data);
        $this->assertSame('Sample Game Widget', $widget->title);
    }

    public function testWidgetQueryPolymorphicTypeResolution(): void
    {
        // 1. WidgetQueryTypeData
        $query1 = new WidgetQuery([
            'id' => 'q1',
            'chat_instance' => 'ci_1',
            'data' => 'raw_data',
            'from' => ['id' => 10, 'is_bot' => false, 'first_name' => 'Alice'],
            'type' => [
                'type' => 'data',
            ],
        ]);
        $this->assertInstanceOf(WidgetQueryTypeData::class, $query1->type);
        $this->assertSame('data', $query1->type->type);

        // 2. WidgetQueryTypeSignData
        $query2 = new WidgetQuery([
            'id' => 'q2',
            'chat_instance' => 'ci_2',
            'data' => 'sign_request',
            'from' => ['id' => 11, 'is_bot' => false, 'first_name' => 'Bob'],
            'type' => [
                'type' => 'sign_data',
                'sign_data' => '{"domain":"bot123","hash":"0xabc"}',
                'wallet' => [
                    'address' => 'EQBvW8Z5huBkMJYdn3PCDnTWKKJ2PEius-Bingo',
                ],
            ],
        ]);
        $this->assertInstanceOf(WidgetQueryTypeSignData::class, $query2->type);
        $this->assertSame('sign_data', $query2->type->type);
        $this->assertSame('{"domain":"bot123","hash":"0xabc"}', $query2->type->signData);
        $this->assertInstanceOf(WidgetQueryWallet::class, $query2->type->wallet);
        $this->assertSame('EQBvW8Z5huBkMJYdn3PCDnTWKKJ2PEius-Bingo', $query2->type->wallet->address);

        // 3. WidgetQueryTypeSentTransaction
        $query3 = new WidgetQuery([
            'id' => 'q3',
            'chat_instance' => 'ci_3',
            'data' => 'tx_alert',
            'from' => ['id' => 12, 'is_bot' => false, 'first_name' => 'Charlie'],
            'type' => [
                'type' => 'sent_transaction',
            ],
        ]);
        $this->assertInstanceOf(WidgetQueryTypeSentTransaction::class, $query3->type);
        $this->assertSame('sent_transaction', $query3->type->type);
    }

    public function testSendWidgetMethod(): void
    {
        $bot = Telegram::fake([
            'sendWidget' => [
                'message_id' => 101,
                'date' => 1700000000,
                'chat' => ['id' => 123456, 'type' => 'private'],
                'widget' => ['url' => 'https://example.com/widget.html'],
            ],
        ]);

        $bot->sendWidget(
            chatId: 123456,
            html: '<div>Hello Widget</div>',
            width: 320,
            height: 240,
            publicData: 'public_config_v1'
        );

        $bot->assertSent('sendWidget', function (Request $req): bool {
            return $req->parameters['chat_id'] === 123456
                && $req->parameters['html'] === '<div>Hello Widget</div>'
                && $req->parameters['width'] === 320
                && $req->parameters['height'] === 240
                && $req->parameters['public_data'] === 'public_config_v1';
        });
    }

    public function testSendSharedWidgetMethod(): void
    {
        $bot = Telegram::fake([
            'sendSharedWidget' => [
                'message_id' => 102,
                'date' => 1700000000,
                'chat' => ['id' => 999888, 'type' => 'private'],
            ],
        ]);

        $bot->sendSharedWidget(
            chatId: 999888,
            widgetId: 'wid_shared_001'
        );

        $bot->assertSent('sendSharedWidget', function (Request $req): bool {
            return $req->parameters['chat_id'] === 999888
                && $req->parameters['widget_id'] === 'wid_shared_001';
        });
    }

    public function testEditMessageWidgetDataAutoInjectsChatAndMessageId(): void
    {
        $bot = Telegram::fake([
            'editMessageWidgetData' => true,
        ]);

        $update = new Update([
            'update_id' => 2001,
            'widget_query' => [
                'id' => 'wq_edit_1',
                'chat_instance' => 'ci_1',
                'data' => 'update_trigger',
                'from' => ['id' => 100, 'is_bot' => false, 'first_name' => 'User1'],
                'chat' => ['id' => -100999, 'type' => 'supergroup'],
                'message_id' => 55,
                'type' => ['type' => 'data'],
            ],
        ]);

        $bot->setUpdate($update);

        // Call editMessageWidgetData with only publicData and chatData
        $bot->editMessageWidgetData(publicData: 'new_public_payload', chatData: 'new_chat_payload');

        $bot->assertSent('editMessageWidgetData', function (Request $req): bool {
            return $req->parameters['chat_id'] === -100999
                && $req->parameters['message_id'] === 55
                && $req->parameters['public_data'] === 'new_public_payload'
                && $req->parameters['chat_data'] === 'new_chat_payload';
        });
    }

    public function testEditMessageWidgetDataAutoInjectsInlineMessageId(): void
    {
        $bot = Telegram::fake([
            'editMessageWidgetData' => true,
        ]);

        $update = new Update([
            'update_id' => 2002,
            'widget_query' => [
                'id' => 'wq_inline_1',
                'chat_instance' => 'ci_2',
                'data' => 'inline_update',
                'from' => ['id' => 101, 'is_bot' => false, 'first_name' => 'User2'],
                'inline_message_id' => 'inline_msg_xyz987',
                'type' => ['type' => 'data'],
            ],
        ]);

        $bot->setUpdate($update);

        $bot->editMessageWidgetData(publicData: 'inline_updated_data');

        $bot->assertSent('editMessageWidgetData', function (Request $req): bool {
            return $req->parameters['inline_message_id'] === 'inline_msg_xyz987'
                && $req->parameters['public_data'] === 'inline_updated_data'
                && !isset($req->parameters['chat_id']);
        });
    }

    public function testEditSharedWidgetDataMethod(): void
    {
        $bot = Telegram::fake([
            'editSharedWidgetData' => true,
        ]);

        $bot->editSharedWidgetData(widgetId: 'shared_widget_99', publicData: 'new_shared_payload');

        $bot->assertSent('editSharedWidgetData', function (Request $req): bool {
            return $req->parameters['widget_id'] === 'shared_widget_99'
                && $req->parameters['public_data'] === 'new_shared_payload';
        });
    }

    public function testFluentWidgetQueryRouterMatchingWithPlaceholders(): void
    {
        $bot = new Telegram('123456:TEST_TOKEN');

        $matchedItem = null;
        $matchedCategory = null;

        $bot->onWidgetQuery('cart:{category}:{item_id}', function (Update $update, string $category, string $item_id) use (&$matchedCategory, &$matchedItem) {
            $matchedCategory = $category;
            $matchedItem = $item_id;
        });

        $update = new Update([
            'update_id' => 2003,
            'widget_query' => [
                'id' => 'wq_cart_1',
                'chat_instance' => 'ci_3',
                'data' => 'cart:books:42',
                'from' => ['id' => 300, 'is_bot' => false, 'first_name' => 'Buyer'],
                'type' => ['type' => 'data'],
            ],
        ]);

        $bot->router->dispatch($update, $bot);

        $this->assertSame('books', $matchedCategory);
        $this->assertSame('42', $matchedItem);
    }

    public function testAttributeControllerWidgetQueryRouting(): void
    {
        $bot = new Telegram('123456:TEST_TOKEN');
        $controller = new TestWidgetController();
        $bot->router->registerController($controller);

        $update = new Update([
            'update_id' => 2004,
            'widget_query' => [
                'id' => 'wq_action_1',
                'chat_instance' => 'ci_4',
                'data' => 'action:checkout:item_888',
                'from' => ['id' => 400, 'is_bot' => false, 'first_name' => 'Buyer'],
                'type' => ['type' => 'data'],
            ],
        ]);

        $bot->router->dispatch($update, $bot);

        $this->assertSame('checkout', $controller->handledAction);
        $this->assertSame('item_888', $controller->handledId);
    }
}
