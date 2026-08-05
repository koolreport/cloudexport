# Change Log

## Version 5.0.0
1. Added the version 2 service host at `https://service.chromeheadless.io/v2`, running an up-to-date Chromium engine with the accompanying security improvements. Select it either by passing it as the second argument, `chromeHeadlessio($secretToken, $serviceHost)`, or by setting `"serviceHost" => "https://service.chromeheadless.io/v2"` in `settings()`. The same second argument works on `khtml()` and `phantomjs()`.
2. The version 2 host works with either client version. A `chromeheadlessio/php-client` 1.x client can point at it unchanged and gets the newer engine and the server-side improvements, because the request shape is the same and the host is just a setting.
3. Resource caching is the part that does need `chromeheadlessio/php-client` 2.x, since the manifest is built client-side. With a 2.1.0 or later client on the version 2 host, the resource cache and `cacheCustom` scope `global` are on by default, so KoolReport's own library resources are uploaded once for the whole service instead of once per export. No `resourceCache` block is needed; `"enabled" => false` opts back out.
4. The version 1 host at `https://service.chromeheadless.io` remains the default and is unchanged. Existing code that does not set `serviceHost` keeps its current behaviour, including the resource cache staying off unless explicitly enabled.

## Version 4.3.0
1. Accept `chromeheadlessio/php-client` 2.0.0 alongside 1.x (`^1 || ^2`). No code change is required: the 2.0.0 API is additive, so existing installs may stay on 1.x indefinitely.
2. Version 2.0.0 of the client brings an opt-in resource cache, which omits assets already cached server-side from the upload zip — relevant here because KoolReport's own library resources are otherwise re-zipped into every export request. Enable it through `settings()`, see README.
3. Version 2.0.0 also brings bounded resource and page timeouts, opt-in retry with exponential backoff, opt-in parallel resource downloads, and `getWarnings()` for inspecting failed resource fetches.
4. All of the above are **off by default**. On 2.0.0 with nothing opted into, the request is byte-identical to 1.x.

## Version 4.2.1
1. Remove outdated `FileHandler` class

## Version 4.2.0
1. Fix dynamic properties in PHP 8.2
2. Update Chromeheadlessio's php-client library version.

## Version 4.1.0

1. Add "serviceHost" argument to chromeheadless($token, $serviceHost), khtml($token, $serviceHost), phantomjs($token, $serviceHost) methods beside settings().


## Version 4.0.0

1. Make authentication token optional to work with local export server
2. Add `serviceHost` and `serviceUrl` settings for cloud export to work with local export server
3. Update chromeheadlessio/php-client version

## Version 3.0.1

1. Update chromeheadlessio/php-client version

## Version 3.0.0

1. add phantomjs engine support

## Version 2.1.1

1. Update chromeheadlessio/php-client version

## Version 2.1.0

1. Update chromeheadlessio/php-client version

## Version 2.0.0

1. Add wkhtmltopdf engine support

## Version 1.5.1

1. Change chromeheadlessio php-client version

## Version 1.5.0

1. Change chromeheadlessio php-client version
2. Change README

## Version 1.4.0

1. Change README

## Version 1.3.0

1. Fix `resourcePatterns` for KoolReport page

## Version 1.2.0

1. Fix the `settings()` function name

## Version 1.1.0

1. Adding the `settings()` methods to ChromeHeadlesIoService to add additional settings for page rendering.

## Version 1.0.0

1. Build general structure to implement the cloud export service
2. Provide connector to service of ChromeHeadlesscd