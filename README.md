# Alert For Laravel

Create customizable UI alerts in Laravel.

## Requirements

-   PHP >= 8.1
-   Laravel >= 9.0

## Installation

Install the package via Composer:

```sh
composer require mayankjanidev/alert-for-laravel
```

## Basic Usage

```php
use Mayank\Alert\Alert;

Alert::info()->create();
```

Show the alert using the blade component:

```php
<x-alert />

// if needed, add your own classes
<x-alert class="mb-4 max-w-lg" />
```

Customize the alert message:

```php
Alert::info()->description('Profile updated.')->create();
```

Add a title to your alerts (optional):

```php
Alert::info()->title('Account Updated')->description('Your profile details were successfully updated.')->create();
```

## Config

Though completely optional, you can publish the config file to customize the below settings:

-   `session_key` is used to set alert message in the session. Default is `alert`.
-   `theme` is used to set the styling for alert message. Default is `default`.

```php
php artisan vendor:publish --provider=Mayank\Alert\ServiceProvider --tag=config
```

## Styling

This package does not depend on any external css or js and should work out of the box without any setup.
However, you can customize the default design by publishing the views.

```php
php artisan vendor:publish --provider=Mayank\Alert\ServiceProvider --tag=views
```

### TailwindCSS

If you already have TailwindCSS installed, this package provides Tailwind specific design.

Update the config to switch to Tailwind.

```php
// config/alert.php

return [
    'theme' => 'tailwind'
];
```

Also add the path to the tailwind config so it does not purge the classes when building the app.css file.

```javascript
// tailwind.config.js

content: [
    ...
    "./vendor/mayankjanidev/alert-for-laravel/resources/views/components/tailwind/*.blade.php",
],
```

You can also customize Tailwind design by publishing its views:

```php
php artisan vendor:publish --provider=Mayank\Alert\ServiceProvider --tag=tailwind
```

## Alert Types

Currently, 4 types are supported: info, success, warning and failure.
Use them like:

```php
Alert::info()->create();
Alert::success()->create();
Alert::warning()->create();
Alert::failure()->create();
```

### Custom Alert Types

Use the `custom()` method to create your own alert types.

```php
Alert::custom('danger')->create();
```

Make sure to create the appropriate view file at `resources.views.vendor.alert.components.danger`

## Model Alerts

Managing alerts for model specific events like created, updated and deleted throughout your app can feel repetitive.
Use the `model()` method to automatically manage that.

```php
use App\Models\Post;

$post = $post->save();

Alert::model($post)->create();
```

This will output "Post was successfully updated.".

Similar messages will be shown for the created and deleted events. It will automatically detect the state of your model.

### Customize text for model alerts

Publish the lang file and customize it to your liking.

```php
php artisan vendor:publish --provider=Mayank\Alert\ServiceProvider --tag=lang
```

```php
// lang/en/messages.php

return [
    'model' => [
        'created' => [
            'description' => ':model_name was created.',
        ],
        'updated' => [
            'description' => ':model_name was updated.',
        ],
        'deleted' => [
            'description' => ':model_name was deleted.',
        ]
    ],
];
```

You can also override text for specific models:

```php
// lang/en/messages.php

return [
    'post' => [
        'created' => [
            'description' => 'Post was successfully published.',
        ],
        'updated' => [
            'description' => 'Post was successfully updated.',
        ],
        'deleted' => [
            'description' => 'Post was sent to trash.',
        ]
    ],
];
```

Title is also supported for alert models:

```php
// lang/en/messages.php

return [
    'model' => [
        'created' => [
            'title' => ':model_name Created.',
            'description' => ':model_name was created.'
        ],
    ],
];
```

### Custom actions for model alerts

If you performed a custom action on a model other than create, update or delete, then you can specify it using the `action()` method.

```php
Alert::model($post)->action('bookmarked')->create();
```

```php
// lang/en/messages.php

return [
    'post' => [
        'bookmarked' => [
            'description' => 'Post was added to bookmarks.',
        ],
    ],
];
```

### Custom lang parameters

Lang parameters are supported:

```php
Alert::model($post, ['title' => $post->title])->action('bookmarked')->create();
```

```php
// lang/en/messages.php

return [
    'post' => [
        'updated' => [
            'description' => 'Post :title was updated.',
        ],
    ],
];
```

## Custom Entity Alerts

If you want the same features of a model alert on a custom entity, use the `for()` method.

```php
Alert::for('settings')->action('profile_updated')->create();
```

```php
// lang/en/messages.php

return [
    'settings' => [
        'profile_updated' => [
            'description' => 'Your profile details were updated.',
        ]
    ]
];
```

All the model alert features like lang files, custom actions and lang parameters behave the same way for custom entity alerts.

## Meta Data

You can add additional meta data to an alert. Good use cases could be links.

```php
Alert::for('order')->action('completed')->meta(['track_order_link' => 'https://example.com'])->create();
```

## JS Frameworks

Though this package does not supply any js components for frameworks like vue or react, you can still use the alert data to build your own component.

Use the `json()` method to get the current alert data in a json object.

```php
<alert :message="{{ Alert::json() }}"></alert>
```

## License

This package is released under the [MIT License](LICENSE).
