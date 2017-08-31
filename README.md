# agency-iha

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Structure

If any of the following are applicable to your project, then the directory structure should follow industry best practises by being named the following.

```
bin/        
config/
src/
tests/
vendor/
```


## Install

Via Composer

``` bash
$ composer require bilginpro/agency-iha
```

## Usage

``` php
$crawler = new \BilginPro\Agency\Iha\Crawler([
    'userCode' => 'your-user-code',
    'userName' => 'your-user-name',
    'password' => 'your-password',
    'limit' => 10, // optional
    'summaryLength' => 150 // optional
]);

$news = $crawler->crawl();
```
Calling `$crawler->crawl` will return an array like this:

```php
[{
		"code": "20170831AW161286",
		"title": "Title of the news 1",
		"summary": "Summary...",
		"content": "Content 1",
		"created_at": "31.08.2017 15:56:12",
		"category": "Genel",
		"city": "Istanbul",
		"images": ["http:\/\/path\/to\/news1\/image1", "http:\/\/path\/to\/news1\/image2"]
	},
	{
		"code": "20170831AW161287",
		"title": "Title of the news 2",
		"summary": "Summary...",
		"content": "Content 2",
		"created_at": "31.08.2017 15:56:12",
		"category": "Genel",
		"city": "Ankara",
		"images": ["http:\/\/path\/to\/news2\/image1", "http:\/\/path\/to\/news2\/image2"]
	}
]
```
## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email yavuz@bilgin.pro instead of using the issue tracker.

## Credits

- [Yavuz Selim Bilgin][link-ysb]
- [Murat Paksoy][link-mp]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bilginpro/agency-iha.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bilginpro/agency-iha/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/bilginpro/agency-iha.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/bilginpro/agency-iha.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bilginpro/agency-iha.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bilginpro/agency-iha
[link-travis]: https://travis-ci.org/bilginpro/agency-iha
[link-scrutinizer]: https://scrutinizer-ci.com/g/bilginpro/agency-iha/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/bilginpro/agency-iha
[link-downloads]: https://packagist.org/packages/bilginpro/agency-iha
[link-ysb]: https://github.com/ysb
[link-mp]: https://github.com/slavesoul
[link-contributors]: ../../contributors
