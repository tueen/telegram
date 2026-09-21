# Payments & Telegram Stars

Telegram allows bots to sell digital goods, physical goods, services, and subscriptions. With the introduction of **Telegram Stars (`XTR`)**, bots can accept frictionless in-app payments across iOS, Android, and Desktop without needing separate merchant gateway accounts.

---

## 1. Selling Digital Goods with Telegram Stars (`XTR`)

When selling digital goods, bots must use `currency: 'XTR'`.

### Sending a Star Invoice (`sendInvoice`)

```php
use Tueen\Telegram\Telegram;

$telegram = new Telegram('YOUR_BOT_TOKEN');

// Send invoice in Telegram Stars:
$telegram->sendInvoice(
    chatId: 12345678,
    title: '👑 Tueen VIP Membership (1 Month)',
    description: 'Unlock exclusive VIP features, unlimited downloads, and premium support.',
    payload: 'order_vip_user_12345678', // Bot-defined internal invoice ID
    currency: 'XTR',                     // Always 'XTR' for Telegram Stars
    prices: [
        ['label' => 'VIP Membership', 'amount' => 50] // 50 Telegram Stars
    ]
);
```

### Generating an Invoice Link (`createInvoiceLink`)

If you want to share a payment link in channels, Web Apps, or via inline buttons:

```php
$link = $telegram->createInvoiceLink(
    title: 'Digital E-Book',
    description: 'The Ultimate Guide to Modern PHP 8.4',
    payload: 'order_ebook_99',
    currency: 'XTR',
    prices: [
        ['label' => 'E-Book Copy', 'amount' => 25]
    ]
);

echo "Payment Link: {$link->value}\n"; // E.g. https://t.me/invoice/...
```

---

## 2. The Payment Flow

A Telegram payment consists of two critical updates:
1. **`pre_checkout_query`**: Sent by Telegram just before charging the user. Your bot must approve this within 10 seconds.
2. **`successful_payment`**: Sent inside a `Message` update once the transaction is completed.

### Handling `pre_checkout_query`

```php
use Tueen\Telegram\Telegram;
use Tueen\Telegram\Types\Update;
use Tueen\Telegram\Enums\UpdateType;

$telegram->run(function (Update $update) use ($telegram) {
    if ($update->type === UpdateType::PRE_CHECKOUT_QUERY) {
        $query = $update->preCheckoutQuery;

        // Verify inventory, user balance, or order state:
        $inStock = true;

        if ($inStock) {
            // Confirm the order:
            $telegram->answerPreCheckoutQuery(
                preCheckoutQueryId: $query->id,
                ok: true
            );
        } else {
            // Reject the order with a user-facing explanation:
            $telegram->answerPreCheckoutQuery(
                preCheckoutQueryId: $query->id,
                ok: false,
                errorMessage: 'Sorry, this digital item is currently out of stock.'
            );
        }
    }
});
```

### Fulfilling `successful_payment`

Once Telegram charges the Stars or currency, the bot receives a message containing `successful_payment`:

```php
use Tueen\Telegram\Enums\MessageType;

$telegram->run(function (Update $update) use ($telegram) {
    $message = $update->getMessage();

    if ($message?->type === MessageType::SUCCESSFUL_PAYMENT) {
        $payment = $message->successfulPayment;

        $telegram->sendMessage(
            chatId: $message->chat->id,
            text: "🎉 Payment received! Transaction ID: {$payment->telegramPaymentChargeId}\n" .
                  "Amount: {$payment->totalAmount} {$payment->currency}\n" .
                  "Your VIP access has been activated!"
        );
    }
});
```

---

## 3. Telegram Stars Refunds (`refundStarPayment`)

If you need to refund a Telegram Stars payment to a customer:

```php
$result = $telegram->refundStarPayment(
    userId: 12345678,
    telegramPaymentChargeId: 'charge_id_from_successful_payment'
);

if ($result->ok() && $result->value) {
    echo "Payment refunded successfully in Telegram Stars.\n";
}
```

---

## 4. Paid Media Messages (`sendPaidMedia`)

Bots and channel owners can publish locked photos or videos that users can unlock by paying Stars:

```php
use Tueen\Telegram\Types\Custom\InputFile;

$telegram->sendPaidMedia(
    chatId: -1001234567890, // Channel or Supergroup
    starCount: 15,          // 15 Stars to unlock
    media: [
        [
            'type' => 'photo',
            'media' => 'https://example.com/premium_preview.jpg'
        ]
    ],
    caption: 'Exclusive backstage photo! Unlock for 15 Stars ⭐'
);
```
