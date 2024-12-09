# vapaaehtoistoiminta (Vapaaehtoistoiminta)

## Installation

Install the theme manually by cloning the repository under your WordPress themes directory
and then removing the git tracking

## Setup

First you need to install required npm packages to start developing. In your theme root run:

```
npm install
```

## Namespacing

The theme is namespaced as `\Geniem\Theme`. WordPress does not support namespaces for template
files and thus they do not adhere to the namespace.

## Translations and textdomain

Translations and the theme textdomain are loaded from under the `/lang` directory.
Replace the `vapaaehtoistoiminta` string with your theme textdomain within all project files with a
case ***sensitive*** search and replace.
Then rename the `.pot` file under the `/lang` directory with the new theme textdomain.

## Polylang

The theme has built-in support for [Polylang][wp-polylang].

## Advanced Custom Fields
Be sure that [Advanced Custom Fields][acf] is installed and active.
Theme settings will be available as own custom post type.

Language specific theme settings are available when both Advanced Custom Fields and Polylang.

## Helsinki Design System

The theme has been designed to make use of the [[HSD](https://github.com/City-of-Helsinki/helsinki-design-system)].

## Theme info

- Namespace: `\Geniem\Theme`

Make sure to change theme and author info in `style.css`.

## Theme directory structure

The Theme consists of the following directories:

- `/assets` - Scripts and styles.
- `/lang` - Theme localization files.
- `/lib` - Theme backend libraries following [PSR-4 Autoloader][psr4] specifications.
- `/page-templates` - Page templates.
- `/partials` - Partials.


## Partial directory structure

The partials in Theme have been organized in the following way:
- `/partials/` - The files in the partials root directory are the main partials.
- `/partials/views/<VIEW>` - Contains partials that are used _only_ by a specific view.

## Theme development guide

### Post types

All post types defined in the theme should be written in the `PostType` directory in a class implementing the `\Geniem\Theme\Interfaces\PostType` interface and registering itself in the wanted hook via `hooks()` method.

#### Default classes

The theme comes with predefined classes for WordPress' default post types. Use these to handle data for them.

- **Geniem\Theme\PostType\Post**: The class presentation of the default post type `post`.
- **Geniem\Theme\PostType\Page**: The class presentation of the default post type `page`.
- **Geniem\Theme\PostType\Attachment**: The class presentation of the default post type `attachment`.

### Taxonomies

All taxonomies defined in the theme should be written in the `Taxonomy` directory in a class implementing the `\Geniem\Theme\Interfaces\Taxonomy` interface and registering itself in the wanted hook via `hooks()` method.

## Global helper functions

- The ThemeController() function allows you to retrieve a Controller instance added to a class instance, such as PostTypeController.

```php
// @codingStandardsIgnoreStart
$model = ThemeController()->get_class( 'PostTypeController' );
// @codingStandardsIgnoreEnd
```

```php
// @codingStandardsIgnoreStart
$model = ModelController()->get_class( 'Header' );
// @codingStandardsIgnoreEnd
```

### Users and roles

Please use our [devgeniem/wp-geniem-roles][wp-geniem-roles] package to modify Users and roles.

### Advanced Custom Fields

The ACFController class handles the loading of the ACF fields from the `lib/ACF` directory.
The files in the directory are only required by the controller, so all the logic must be
implemented by the developer themself.

### Logging

`\Geniem\Theme\Logger` handles logging in [`syslog`](https://en.wikipedia.org/wiki/Syslog) compatible logging levels.

The logged messages are finally passed to `error_log()` function, which in part uses configured logging destination.

To control what is finally outputted to the log, you can change `GENIEM_LOG_LEVEL` environment variable.
Below is a quick table for level (used to control the logging level), class method, and description
when to use in your code.

| Level | Method        | Description                                          |
|-------|---------------|------------------------------------------------------|
| 100   | `debug()`     | Debug-level messages.                                |
| 200   | `info()`      | Informational messages.                              |
| 250   | `notice()`    | Normal but significant conditions.                   |
| 300   | `warning()`   | When something's missing, or uses deprecated option. |
| 400   | `error()`     | Something's gone wrong, but code can still continue. |
| 500   | `critical()`  | Something has gone critically wrong.                 |
| 550   | `alert()`     | A condition that should be corrected immediately.    |
| 600   | `emergency()` | System is unusable. Crash and burn.                  |

The Easiest way to use the logger is like this:

```php
try {
    throw new Exception( 'This is an example.' );
}
catch ( \Exception $e ) {
    ( new \Geniem\Theme\Logger() )->error( $e->getMessage(), $e->getTrace() );
}
```

##  Assets and Webpack

[webpack][webpack-what-is] is used to compile the assets.
Use [npm][npm] to install packages and require JavaScript files in `assets/scripts/main.js`
and import SCSS files in `assets/styles/main.scss`.
The directory under which the assets are build is `assets/dist`.

You should only enqueue files that are under `assets/dist` in your theme's PHP code!
To use node modules, import them into your theme scripts.

The URL to be used with the assets has been defined in `/lib/Assets.php` with the `DPT_ASSET_URI` constant.
It points to `https://{site_domain}/{path_to_themes_folder}/themename/dist` by default.
To use another source for assets this value can be changed by defining the constant for example in
the `wp-config.php` file with a custom URI.

## Asset versioning

The style and script files are automatically enqueued with the current theme version.
To bust browser cache on asset updates change the theme version in the `style.css` file comments.

### Development

Run webpack in the theme root in your local environment.

Run with the npm script:

```
npm run watch
```

These commands will compile *unminified* versions of your assets.

### Production

Build _minified_ versions for production with the npm script:

```
npm run build
```

This command will compile *minified* versions of your assets.

### Testing with Browsersync

The project includes a [BrowserSync][browsersync] setup that will proxy your site to `localhost:3000`,
and an IP address on your local network. This enables you to develop and test your project simultaneously with
multiple browsers and devices with a scroll and click synchronisation. The settings for Browsersync are in
`webpack.config.js`.

You should change at least the `wpProjectUrl` constant to point to your local development server.
By default, Browsersync monitors CSS, JS, PHP and Dust files for changes and reloads all browsers automatically.
For more information on available settings etc, read the [webpack plugin documentation][npm-browsersync-webpack]
and the [Browsersync documentation][browsersync-docs].

## JavaScript development guide

The theme's Webpack config uses [Babel][babeljs] to compile [ES6][wikipedia-es6] into [ES5][wikipedia-es5].

### Theme scripts

The theme's main JS file `theme.js` holds the main theme class.
The `Theme` class runs other theme JS classes automatically on the document ready event.
To enable autoloading on JS side define your template scripts as follows:

1. Create a separate script file for each WordPress template, for example `page-frontpage.js`.
    You can also create script files that are loaded anyway regardless of the template.

2. Define a JS class mimicking your page template and export the class reference,
    for example `export default class Search {}`.

3. The document's html element will automatically get a class from template slug,
    for example `<html class="search">`.

4. Import your scripts in `theme.js` and add them into the appropriate controller lists.
    The order of the scripts is essential if you need to access methods from other script classes.

   ```javascript
   // First import the scripts you want to use
   import Common from './common';
   import Search from './search';

   // Add your global scripts here.
   const globalControllers = {
       Common
   };

   // Add your template-specific scripts here.
   const templateControllers = {
       Search
   };
   ```

5. The `Theme` class will then automatically run a method called `docReady` when the document is ready.
*Remember to define it in your template class!*

### The `Theme` class

The `Theme` class controls the theme script classes. The class instance is accessible globally and
thus `Theme` is a reserved JavaScript object name in our themes. `Theme` holds all scripts under
corresponding class properties.

Global scripts are under `_globalControllers` and template specific scripts are under `_templateControllers`.
These properties are hash maps meaning each controller is under a key defined with the class name:

```javascript
class Theme {
  constructor() {
      // [...]
      this._templateController = {
        className: classInstance
      }
      // [...]
  }
}
```

The controller classes are instantiated by the set methods (`setGlobalControllers` and `setTemplateControllers`)
run by the `main.js`. Thus, an instance of a class is created and accessible before the document is ready and loaded.
Template controllers are instantiated only if the corresponding style class name is defined for the `html` element
in the DOM. If the template controller class is not instantiated, you can still access it statically by
calling `Theme.{ClassName}`.

**This class is not to be modified! Use other script files to do the magic in you theme!**

#### Accessing controllers

To access a class instance for example in some inline script, fetch it as follows:

```javascript
var frontpageInstance = Theme.getController("PageFrontpage");
```

To access a class reference, fetch it as follows:

```javascript
var commonClass = Theme.Common;
```

Class references are useful if you need global static methods. For instance, you might create a
static global Vanilla JS query selector under `common.js`:

```javascript
export default class Common {
    // Select a list of matching elements, context is optional.
    static $(selector, context = undefined) {
        return (context || document).querySelectorAll(selector);
    }
}
```

...and then use it as follows:

```javascript
let element = Theme.Common.$('.my-element');
```

#### `docReady` methods

If you manipulate the DOM dynamically and need to rerun *all* `docReady` methods for the current
template *(controlled by the current html element classlist)*, run it with the class method:

```javascript
Theme.runDocReady();
```

Alternatively you can run the `docReady` for a single controller instance:

```javascript
Theme.getController('PageFrontpage').docReady();
```

#### `init` methods

The `Theme` class runs a method called `init` for all the global scripts, and the currently defined template
scripts after all scripts are instantiated. If you want access other script classes on the class constructor,
they might not be accessible yet due to the `require` order. By running your scripts on the `init` method all
other script classes are already loaded and instantiated. Here is an example usage:

```javascript
class PageFrontpage {
    init() {
      // I need to acces the sidebar!
      this.Search = Theme.getController('Search');
      // Now we can run stuff before the docReady..
      this.Search.searchMakesMeDoStuff();
    }
}
```

### The `Common` class

The theme assets include a default common script class called `Common`. Use this as the default class for all of your
 global methods and properties. For instance, you might handle the main menu in the `Common` class.

*Do not bloat this class! Do not be afraid to introduce more global controller classes
if your script files get too long! What ever you do, [D.R.Y][wikipedia-dry]!*

#### Useful methods under `Common`

#### **`stop`**

This **static** method enables you to safely stop the default event on event listener callbacks. Use it as follows:

```javascript
function myEventCallback(e) {
  Theme.Common.stop(e);
  // Do some other stuff with the event..
}
```

#### `**$**`

This **static** method wraps the `querySelectorAll` function and lets you select a list of matching elements.
To optimize the query add a context element and only query under it.

```javascript
let elements = Theme.Common.$('.my-element', myParentElementObject);
```

#### **`$1`**

This **static** method wraps the `querySelector` functions to select a single element from the `DOM`.
To optimize the query add a context element and only query under it.

```javascript
let element = $1('.my-element', myParentElementObject);
```

### Global event listener and data-cmd attributes

Theme class adds global click event listener which you can use by adding data-cmd and data-ctrl attributes
to your html element, where `data-ctrl` defines the JavaScript controller class and `data-cmd` the method to be called.
```
<button data-cmd="doSomething" data-ctrl="MyAwesomeController" />
```

The method receives two parameters. The first parameter is the event, and the second is the actual
element that has the `data-cmd` and `data-ctrl` attributes.

So in the Class `MyAwesomeController` we could have a method like this:

```javascript
class MyAwesomeController {
    /**
     * Set aria-expanded to true when element is clicked.
     *
     * @param {object|Event}              e  The button click event.
     * @param {object|HTMLElement|jQuery} el The dom object of the element that was clicked.
     * @return {void}
     */
    doSomething(e, el) {
        el.setAttribute('aria-expanded', 'true');
    }
}
```

### External libraries

#### jQuery

All scripts rely on WordPress enqueueing jQuery as an external library.
WordPress exposes jQuery as a public library under the global context.
To use the `$` shorthand, add following to the beginning of your script file:

```javascript
const $ = jQuery;
```

#### Lodash

[Lodash][lodash] is a modern JavaScript utility library delivering modularity, performance & extras.
See the [documentation][lodash-docs] for usage guide.
To import Lodash, add the following to the beginning of your script file:

```javascript
import _ from 'lodash';
```

[acf]: https://www.advancedcustomfields.com/
[babeljs]: https://babeljs.io/
[browsersync-docs]: https://browsersync.io/docs
[browsersync]: https://browsersync.io
[HSD]: https://github.com/City-of-Helsinki/helsinki-design-system
[lodash-docs]: https://lodash.com/docs/
[lodash]: https://lodash.com/
[npm-browsersync-webpack]: https://www.npmjs.com/package/browser-sync-webpack-plugin
[npm]: https://www.npmjs.com/
[psr4]: https://www.php-fig.org/psr/psr-4/
[webpack-what-is]: https://webpack.github.io/docs/what-is-webpack.html
[wikipedia-dry]: https://en.wikipedia.org/wiki/Don%27t_repeat_yourself
[wikipedia-es5]: https://en.wikipedia.org/wiki/ECMAScript#5th_Edition
[wikipedia-es6]: https://en.wikipedia.org/wiki/ECMAScript#6th_Edition_-_ECMAScript_2015
[wp-geniem-roles]: https://github.com/devgeniem/wp-geniem-roles
[wp-polylang]: https://wordpress.org/plugins/polylang/
