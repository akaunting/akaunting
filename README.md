# Akaunting™

 ![Latest Stable Version](https://img.shields.io/github/release/akaunting/akaunting.svg) ![Total Downloads](https://img.shields.io/github/downloads/akaunting/akaunting/total.svg) [![Crowdin](https://d322cqt584bo4o.cloudfront.net/akaunting/localized.svg)](https://crowdin.com/project/akaunting) ![Build Status](https://travis-ci.com/akaunting/akaunting.svg) [![Backers on Open Collective](https://opencollective.com/akaunting/backers/badge.svg)](#backers) [![Sponsors on Open Collective](https://opencollective.com/akaunting/sponsors/badge.svg)](#sponsors)

Akaunting is a free, open source and online accounting software designed for small businesses and freelancers. It is built with modern technologies such as Laravel, Bootstrap, jQuery, RESTful API etc. Thanks to its modular structure, Akaunting provides an awesome App Store for users and developers.

* [Home](https://akaunting.com) - The house of Akaunting
* [Blog](https://akaunting.com/blog) - Get the latest news
* [Forum](https://akaunting.com/forum) - Ask for support
* [Documentation](https://akaunting.com/docs) - Learn how to use
* [Translations](https://crowdin.com/project/akaunting) - Akaunting in your language
* 
Contents
Selected Version
Azure Bot Service
Request payment
12/12/2017
9 minutes to read
Contributors
Duc Cash Vo  Kamran Iqbal  CathyQian  Robert Standefer  Kim Brandl - MSFT all
In this article
Prerequisites
Payment process overview
Payment Bot sample
Requesting payment
User experience
Processing callbacks
Testing a payment bot
Additional resources
 Note
This topic applies to SDK v3 release. You can find the documentation for the latest version of the SDK v4 here.

If your bot enables users to purchase items, it can request payment by including a special type of button within a rich card. This article describes how to send a payment request using the Bot Builder SDK for Node.js.
Prerequisites
Before you can send a payment request using the Bot Builder SDK for Node.js, you must complete these prerequisite tasks.
Register and configure your bot

Update your bot's environment variables for MicrosoftAppId and MicrosoftAppPassword to the app ID and password values that were generated for your bot during the registration process.
 Note
To find your bot's AppID and AppPassword, see MicrosoftAppID and MicrosoftAppPassword.
Create and configure merchant account

Create and activate a Stripe account if you don't have one already.
Sign in to Seller Center with your Microsoft account.
Within Seller Center, connect your account with Stripe.
Within Seller Center, navigate to the Dashboard and copy the value of MerchantID.
Update the PAYMENTS_MERCHANT_ID environment variable to the value that you copied from the Seller Center Dashboard.
Payment process overview
The payment process comprises three distinct parts:
The bot sends a payment request.
The user signs in with a Microsoft account to provide payment, shipping, and contact information. Callbacks are sent to the bot to indicate when the bot needs to perform certain operations (update shipping address, update shipping option, complete payment).
The bot processes the callbacks that it receives, including shipping address update, shipping option update, and payment complete.
Your bot must implement only step one and step three of this process; step two takes place outside the context of your bot.
Payment Bot sample
The Payment Bot sample provides an example of a bot that sends a payment request by using Node.js. To see this sample bot in action, you can try it out in web chat, add it as a Skype contact, or download the payment bot sample and run it locally using the Bot Framework Emulator.
 Note
To complete the end-to-end payment process using the Payment Bot sample in web chat or Skype, you must specify a valid credit card or debit card within your Microsoft account (i.e., a valid card from a U.S. card issuer). Your card will not be charged and the card's CVV will not be verified, because the Payment Bot sample runs in test mode (i.e., PAYMENTS_LIVEMODE is set to false in .env).
The next few sections of this article describe the three parts of the payment process, in the context of the Payment Bot sample.
Requesting payment
Your bot can request payment from a user by sending a message that contains a rich card with a button that specifies type of "payment". This code snippet from the Payment Bot sample creates a message that contains a Hero card with a Buy button that the user can click (or tap) to initiate the payment process.
JavaScript

Copy
var bot = new builder.UniversalBot(connector, (session) => {

  catalog.getPromotedItem().then(product => {

    // Store userId for later, when reading relatedTo to resume dialog with the receipt.
    var cartId = product.id;
    session.conversationData[CartIdKey] = cartId;
    session.conversationData[cartId] = session.message.address.user.id;

    // Create PaymentRequest obj based on product information.
    var paymentRequest = createPaymentRequest(cartId, product);

    var buyCard = new builder.HeroCard(session)
      .title(product.name)
      .subtitle(util.format('%s %s', product.currency, product.price))
      .text(product.description)
      .images([
        new builder.CardImage(session).url(product.imageUrl)
      ])
      .buttons([
        new builder.CardAction(session)
          .title('Buy')
          .type(payments.PaymentActionType)
          .value(paymentRequest)
      ]);

    session.send(new builder.Message(session)
      .addAttachment(buyCard));
  });
});
In this example, the button's type is specified as payments.PaymentActionType, which the app defines as "payment". The button's value is populated by the createPaymentRequest function, which returns a PaymentRequest object that contains information about supported payment methods, details, and options. For more information about implementation details, see app.js within the Payment Bot sample.
This screenshot shows the Hero card (with Buy button) that's generated by the code snippet above.
Payments sample bot
 Important
Any user that has access to the Buy button may use it to initiate the payment process. Within the context of a group conversation, it is not possible to designate a button for use by only a specific user.
User experience
When a user clicks the Buy button, he or she is directed to the payment web experience to provide all required payment, shipping, and contact information via their Microsoft account.
Microsoft payment
HTTP callbacks

HTTP callbacks will be sent to your bot to indicate that it should perform certain operations. Each callback will be an event that contains these property values:
Property	Value
type	invoke
name	Indicates the type of operation that the bot should perform (e.g., shipping address update, shipping option update, payment complete).
value	The request payload in JSON format.
relatesTo	Describes the channel and user that are associated with the payment request.
 Note
invoke is a special event type that is reserved for use by the Microsoft Bot Framework. The sender of an invoke event will expect your bot to acknowledge the callback by sending an HTTP response.
Processing callbacks
When your bot receives a callback, it should verify that the information specified in the callback is valid and acknowledge the callback by sending an HTTP response.
Shipping Address Update and Shipping Option Update callbacks

When receiving a Shipping Address Update or a Shipping Option Update callback, your bot will be provided with the current state of the payment details from the client in the event's value property. As a merchant, you should treat these callbacks as static, given input payment details you will calculate some output payment details and fail if the input state provided by the client is invalid for any reason.  If the bot determines the given information is valid as-is, simply send HTTP status code 200 OK along with the unmodified payment details. Alternatively, the bot may send HTTP status code 200 OK along with an updated payment details that should be applied before the order can be processed. In some cases, your bot may determine that the updated information is invalid and the order cannot be processed as-is. For example, the user's shipping address may specify a country to which the product supplier does not ship. In that case, the bot may send HTTP status code 200 OK and a message populating the error property of the payment details object. Sending any HTTP status code in the 400 or 500 range to will result in a generic error for the customer.
Payment Complete callbacks

When receiving a Payment Complete callback, your bot will be provided with a copy of the initial, unmodified payment request as well as the payment response objects in the event's value property. The payment response object will contain the final selections made by the customer along with a payment token. Your bot should take the opportunity to recalculate the final payment request based on the initial payment request and the customer's final selections. Assuming the customer's selections are determined to be valid, the bot should verify the amount and currency in the payment token header to ensure that they match the final payment request. If the bot decides to charge the customer it should only charge the amount in the payment token header as this is the price the customer confirmed. If there is a mismatch between the values that the bot expects and the values that it received in the Payment Complete callback, it can fail the payment request by sending HTTP status code 200 OK along with setting the result field to failure.
In addition to verifying payment details, the bot should also verify that the order can be fulfilled, before it initiates payment processing. For example, it may want to verify that the item(s) being purchased are still available in stock. If the values are correct and your payment processor has successfully charged the payment token, then the bot should respond with HTTP status code 200 OK along with setting the result field to success in order for the payment web experience to display the payment confirmation. The payment token that the bot receives can only be used once, by the merchant that requested it, and must be submitted to Stripe (the only payment processor that the Bot Framework currently supports). Sending any HTTP status code in the 400 or 500 range to will result in a generic error for the customer.
This code snippet from the Payment Bot sample processes the callbacks that the bot receives.
JavaScript

Copy
connector.onInvoke((invoke, callback) => {
  console.log('onInvoke', invoke);

  // This is a temporary workaround for the issue that the channelId for "webchat" is mapped to "directline" in the incoming RelatesTo object
  invoke.relatesTo.channelId = invoke.relatesTo.channelId === 'directline' ? 'webchat' : invoke.relatesTo.channelId;

  var storageCtx = {
    address: invoke.relatesTo,
    persistConversationData: true,
    conversationId: invoke.relatesTo.conversation.id
  };

  connector.getData(storageCtx, (err, data) => {
    var cartId = data.conversationData[CartIdKey];
    if (!invoke.relatesTo.user && cartId) {
      // Bot keeps the userId in context.ConversationData[cartId]
      var userId = data.conversationData[cartId];
      invoke.relatesTo.useAuth = true;
      invoke.relatesTo.user = { id: userId };
    }

    // Continue based on PaymentRequest event.
    var paymentRequest = null;
    switch (invoke.name) {
      case payments.Operations.UpdateShippingAddressOperation:
      case payments.Operations.UpdateShippingOptionOperation:
        paymentRequest = invoke.value;

        // Validate address AND shipping method (if selected).
        checkout
          .validateAndCalculateDetails(paymentRequest, paymentRequest.shippingAddress, paymentRequest.shippingOption)
          .then(updatedPaymentRequest => {
            // Return new paymentRequest with updated details.
            callback(null, updatedPaymentRequest, 200);
          }).catch(err => {
            // Return error to onInvoke handler.
            callback(err);
            // Send error message back to user.
            bot.beginDialog(invoke.relatesTo, 'checkout_failed', {
              errorMessage: err.message
            });
          });

        break;

      case payments.Operations.PaymentCompleteOperation:
        var paymentRequestComplete = invoke.value;
        paymentRequest = paymentRequestComplete.paymentRequest;
        var paymentResponse = paymentRequestComplete.paymentResponse;

        // Validate address AND shipping method.
        checkout
          .validateAndCalculateDetails(paymentRequest, paymentResponse.shippingAddress, paymentResponse.shippingOption)
          .then(updatedPaymentRequest =>
            // Process payment.
            checkout
              .processPayment(updatedPaymentRequest, paymentResponse)
              .then(chargeResult => {
                // Return success.
                callback(null, { result: "success" }, 200);
                // Send receipt to user.
                bot.beginDialog(invoke.relatesTo, 'checkout_receipt', {
                  paymentRequest: updatedPaymentRequest,
                  chargeResult: chargeResult
                });
              })
          ).catch(err => {
            // Return error to onInvoke handler.
            callback(err);
            // Send error message back to user.
            bot.beginDialog(invoke.relatesTo, 'checkout_failed', {
              errorMessage: err.message
            });
          });

        break;
    }

  });
});
In this example, the bot examines the name property of the incoming event to identify the type of operation it needs to perform, and then calls the appropriate method(s) to process the callback. For more information about implementation details, see app.js within the Payment Bot sample.
Testing a payment bot
To fully test a bot that requests payment, configure it to run on channels that support Bot Framework payments, like Web Chat and Skype. Alternatively, you can test your bot locally using the Bot Framework Emulator.
 Tip
Callbacks are sent to your bot when a user changes data or clicks Pay during the payment web experience. Therefore, you can test your bot's ability to receive and process callbacks by interacting with the payment web experience yourself.
In the Payment Bot sample, the PAYMENTS_LIVEMODE environment variable in .env determines whether Payment Complete callbacks will contain emulated payment tokens or real payment tokens. If PAYMENTS_LIVEMODE is set to false, a header is added to the bot's outbound payment request to indicate that the bot is in test mode, and the Payment Complete callback will contain an emulated payment token that cannot be charged. If PAYMENTS_LIVEMODE is set to true, the header which indicates that the bot is in test mode is omitted from the bot's outbound payment request, and the Payment Complete callback will contain a real payment token that the bot will submit to Stripe for payment processing. This will be a real transaction that results in charges to the specified payment instrument.
Additional resources
Payment Bot sample
Add rich card attachments to messages
Web Payments at W3C
Feedback

Would you like to provide feedback?

Sign in to give feedback
 
Our new feedback system is built on GitHub Issues. Read about this change in our blog post.
There is currently no feedback for this document. Submitted feedback will appear here.
Feedback
English (United States)
Previous Version Docs Blog Contribute Privacy & Cookies Terms of Use Site Feedback Trademarks
## Requirements

* PHP 5.6.4 or higher
* Database (eg: MySQL, PostgreSQL, SQLite)
* Web Server (eg: Apache, Nginx, IIS)
* [Other libraries](https://akaunting.com/docs/requirements)

## Framework

Akaunting uses [Laravel](http://laravel.com), the best existing PHP framework, as the foundation framework and [Modules](https://nwidart.com/laravel-modules) package for Apps.

## Installation

  * Install [Composer](https://getcomposer.org/download)
  * Download the [repository](https://github.com/akaunting/akaunting/archive/master.zip) and unzip into your server
  * Open and point your command line to the directory you unzipped Akaunting
  * Run the following command: `composer install`
  * Finally, launch the [installer](https://akaunting.com/docs/installation)

## Docker

It is possible to containerise Akaunting using the [`docker-compose`](docker-compose.yml) file. Here are a few commands:

```
# Build the app
docker build -t akaunting .

# Run the app
docker-compose up -d

# Make sure you the dependencies are installed
docker-compose exec web composer install

# Stream logs
docker-compose logs -f web

# Access the container
docker-compose exec web /bin/sh

# Stop & Delete everything
docker-compose down -v
```

## Contributing

Fork the repository, make the code changes then submit a pull request.

Please, be very clear on your commit messages and pull requests, empty pull request messages may be rejected without reason.

When contributing code to Akaunting, you must follow the PSR coding standards. The golden rule is: Imitate the existing Akaunting code.

Please note that this project is released with a [Contributor Code of Conduct](https://akaunting.com/conduct). By participating in this project you agree to abide by its terms.

## Translation

If you'd like to contribute translations, please check out our [Crowdin](https://crowdin.com/project/akaunting) project.

## Changelog

Please see [Releases](../../releases) for more information what has changed recently.

## Security

If you discover any security related issues, please email security@akaunting.com instead of using the issue tracker.

## Credits

- [Denis Duliçi](https://github.com/denisdulici)
- [Cüneyt Şentürk](https://github.com/cuneytsenturk)
- [All Contributors](../../contributors)

## Contributors

This project exists thanks to all the people who contribute. [[Contribute](CONTRIBUTING.md)].
[![Contributors](https://opencollective.com/akaunting/contributors.svg?width=890&button=false)](../../contributors)

## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/akaunting#backer)]

[![Backers](https://opencollective.com/akaunting/backers.svg?width=890)](https://opencollective.com/akaunting#backers)

## Sponsors

Support this project by becoming a sponsor. Your logo will show up here with a link to your website. [[Become a sponsor](https://opencollective.com/akaunting#sponsor)]

[![Sponsor 0](https://opencollective.com/akaunting/sponsor/0/avatar.svg)](https://opencollective.com/akaunting/sponsor/0/website)
[![Sponsor 1](https://opencollective.com/akaunting/sponsor/1/avatar.svg)](https://opencollective.com/akaunting/sponsor/1/website)
[![Sponsor 2](https://opencollective.com/akaunting/sponsor/2/avatar.svg)](https://opencollective.com/akaunting/sponsor/2/website)
[![Sponsor 3](https://opencollective.com/akaunting/sponsor/3/avatar.svg)](https://opencollective.com/akaunting/sponsor/3/website)
[![Sponsor 4](https://opencollective.com/akaunting/sponsor/4/avatar.svg)](https://opencollective.com/akaunting/sponsor/4/website)
[![Sponsor 5](https://opencollective.com/akaunting/sponsor/5/avatar.svg)](https://opencollective.com/akaunting/sponsor/5/website)
[![Sponsor 6](https://opencollective.com/akaunting/sponsor/6/avatar.svg)](https://opencollective.com/akaunting/sponsor/6/website)
[![Sponsor 7](https://opencollective.com/akaunting/sponsor/7/avatar.svg)](https://opencollective.com/akaunting/sponsor/7/website)
[![Sponsor 8](https://opencollective.com/akaunting/sponsor/8/avatar.svg)](https://opencollective.com/akaunting/sponsor/8/website)
[![Sponsor 9](https://opencollective.com/akaunting/sponsor/9/avatar.svg)](https://opencollective.com/akaunting/sponsor/9/website)

## License

Akaunting is released under the [GPLv3 license](LICENSE.txt).
