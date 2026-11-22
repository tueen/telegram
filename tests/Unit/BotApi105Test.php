<?php

declare(strict_types=1);

namespace Tueen\Telegram\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tueen\Telegram\Client\Request;
use Tueen\Telegram\Methods\SetMySettings;
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\BotFullInfo;
use Tueen\Telegram\Types\Custom\BooleanResult;
use Tueen\Telegram\Types\RichBlockExpandableBlockQuotation;
use Tueen\Telegram\Types\RichBlockParagraph;
use Tueen\Telegram\Types\User;
use Tueen\Telegram\Types\WriteAccessAllowed;

class BotApi105Test extends TestCase
{
    public function testGetMeReturnsBotFullInfoWithNewFields(): void
    {
        $bot = Telegram::fake([
            'getMe' => [
                'id' => 100200300,
                'is_bot' => true,
                'first_name' => 'TueenBot',
                'username' => 'tueen_bot',
                'active_usernames' => ['tueen_bot', 'tueen_alt_bot'],
                'can_join_groups' => true,
                'can_read_all_group_messages' => false,
                'supports_guest_queries' => true,
                'supports_inline_queries' => true,
                'inline_query_placeholder' => 'Search docs...',
                'needs_inline_query_location' => false,
                'can_connect_to_business' => true,
                'has_main_web_app' => false,
                'has_topics_enabled' => true,
                'allows_users_to_create_topics' => true,
                'has_topic_sidebar' => true,
                'can_manage_bots' => true,
                'supports_join_request_queries' => false,
            ],
        ]);

        $me = $bot->getMe();

        $this->assertInstanceOf(BotFullInfo::class, $me);
        $this->assertSame(100200300, $me->id);
        $this->assertSame(['tueen_bot', 'tueen_alt_bot'], $me->activeUsernames);
        $this->assertSame('Search docs...', $me->inlineQueryPlaceholder);
        $this->assertFalse($me->needsInlineQueryLocation);
        $this->assertTrue($me->hasTopicSidebar);
        $this->assertTrue($me->canManageBots);
    }

    public function testSetMySettingsSerializesParameters(): void
    {
        $bot = Telegram::fake([
            'setMySettings' => true,
        ]);

        $result = $bot->setMySettings(
            supportsInlineQueries: true,
            inlineQueryPlaceholder: 'Type query...',
            needsInlineQueryLocation: false,
            inlineFeedbackShareDenominator: 10,
            canJoinGroups: true,
            hasTopicSidebar: true,
            canCommunicateWithBots: true,
            callbackQueryMode: 'inline_message_id',
        );

        $this->assertInstanceOf(BooleanResult::class, $result);
        $this->assertTrue($result->value);

        $bot->assertSent(SetMySettings::class, function (Request $req): bool {
            return $req->endpoint === 'setMySettings'
                && $req->parameters['supports_inline_queries'] === true
                && $req->parameters['inline_query_placeholder'] === 'Type query...'
                && $req->parameters['needs_inline_query_location'] === false
                && $req->parameters['inline_feedback_share_denominator'] === 10
                && $req->parameters['can_join_groups'] === true
                && $req->parameters['has_topic_sidebar'] === true
                && $req->parameters['can_communicate_with_bots'] === true
                && $req->parameters['callback_query_mode'] === 'inline_message_id';
        });
    }

    public function testWriteAccessAllowedInviterDeserialization(): void
    {
        $writeAccess = new WriteAccessAllowed([
            'inviter' => [
                'id' => 42,
                'is_bot' => false,
                'first_name' => 'Owner',
            ],
        ]);

        $this->assertInstanceOf(User::class, $writeAccess->inviter);
        $this->assertSame(42, $writeAccess->inviter->id);
        $this->assertSame('Owner', $writeAccess->inviter->firstName);
    }

    public function testRichBlockExpandableBlockQuotationUsesBlocks(): void
    {
        $quote = new RichBlockExpandableBlockQuotation([
            'type' => 'expandable_blockquote',
            'blocks' => [
                [
                    'type' => 'paragraph',
                    'text' => ['type' => 'plain', 'text' => 'Nested block'],
                ],
            ],
        ]);

        $this->assertIsArray($quote->blocks);
        $this->assertCount(1, $quote->blocks);
        $this->assertInstanceOf(RichBlockParagraph::class, $quote->blocks[0]);
    }
}
