# Dravencms call to action module

This is a simple call to action module for dravencms

## Instalation

The best way to install dravencms/call-to-action is using  [Composer](http://getcomposer.org/):


```sh
$ composer require dravencms/call-to-action
```

Then you have to register extension in `config.neon`.

```yaml
extensions:
	dravencms.callToAction: Dravencms\CallToAction\DI\CallToActionExtension
```
