# gateway-sms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gateway/sms.svg?style=for-the-badge&logo=composer)](https://packagist.org/packages/gateway/sms)
[![Total Downloads](https://img.shields.io/packagist/dt/gateway/sms.svg?style=for-the-badge&logo=laravel)](https://packagist.org/packages/gateway/sms)

This is a Laravel Package for SMS Gateway Integration. Now Sending SMS is easy.

List of supported gateways:
- log : for test
- [Farazsms](https://farazsms.com)
- Others are under way.

## package: Install

Via Composer

``` bash
$ composer require gateway/sms
```

In the config file you can set the default driver to use for all your SMS. But you can also change the driver at runtime.

Choose what gateway you would like to use for your application. Then make that as default driver so that you don't have to specify that everywhere. But, you can also use multiple gateways in a project.

```php
'default' => 'log',
```

Then fill the credentials for that gateway in the drivers array.

```php
// Eg. for farazsms.
'drivers' => [
    'farazsms' => [
        'url'     => env('FARAZSMS_URL', 'http://rest.ippanel.com'),
        'from'    => env('FARAZSMS_FROM'),
        'api_key' => env('FARAZSMS_API_KEY'),
    ],
    ...
]
```

#### log Configuration:

log is added by default. You just have to change the creadentials in the `textlocal` driver section.

#### Kavenegar Configuration:

In case you want to use Kavenegar. Then you have to pull a composer library first.

```bash
composer require kavenegar/php
```

## Usage

In your code just use it like this.
```php
# On the top of the file.
use Gateway\Sms\Facades\Sms;

...

# In your Controller.
Sms::content("this message")->to(['Number 1', 'Number 2'])->send();
```

## :heart_eyes: Channel Usage

First you have to create your notification using `php artisan make:notification` command.
then `sms` can be used as channel like the below:

```php
namespace App\Notifications;

use Gateway\Sms\Channels\SmsMessage;use Illuminate\Bus\Queueable;
use Gateway\Sms\Channels\SmsChannel;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['sms'];
    }

    /**
     * Get the repicients and body of the notification.
     *
     * @param  mixed  $notifiable
     * @return Builder
     */
    public function toSms($notifiable)
    {
        # for send message.
        return (new SmsMessage())
            ->via('gateway') # via() is Optional
            ->content('this message')
            ->to('some number')
            ->send();
        # for send by pattern.
        return (new SmsMessage())
            ->via('gateway') # via() is Optional
            ->patternCode('mfgfdm')
            ->patternData(['code' => '45215'])
            ->to('some number')
            ->send();
    }
}
```
