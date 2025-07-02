<?php declare(strict_types=1);

namespace AhjDev\PhpTagMaker\Node\Internal;

use AhjDev\PhpTagMaker\HtmlClass;
use AhjDev\PhpTagMaker\Node;
use AhjDev\PhpTagMaker\Node\HtmlTag;

/**
 * @internal
 * A trait that provides convenient static factory methods for all common HTML5 tags.
 * This makes creating tags more fluent and readable (e.g., HtmlTag::div() instead of new HtmlTag('div')).
 */
trait DefaultTags
{
    // --- Document Metadata ---
    public static function head(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('head', ...$value);
    }
    public static function title(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('title', ...$value);
    }
    public static function base(string $uri, string $target): HtmlTag
    {
        return HtmlTag::make('base')->setAttribute('href', $uri)->setAttribute('target', $target);
    }
    public static function link(string $rel, string $uri): HtmlTag
    {
        return HtmlTag::make('link')->setAttribute('rel', $rel)->setAttribute('href', $uri);
    }
    public static function meta(): HtmlTag
    {
        return HtmlTag::make('meta');
    }
    public static function style(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('style', ...$value);
    }

    // --- Sectioning Root ---
    public static function body(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('body', ...$value);
    }

    // --- Content Sectioning ---
    public static function address(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('address', ...$value);
    }
    public static function article(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('article', ...$value);
    }
    public static function aside(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('aside', ...$value);
    }
    public static function footer(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('footer', ...$value);
    }
    public static function header(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('header', ...$value);
    }
    public static function h1(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h1', ...$value);
    }
    public static function h2(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h2', ...$value);
    }
    public static function h3(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h3', ...$value);
    }
    public static function h4(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h4', ...$value);
    }
    public static function h5(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h5', ...$value);
    }
    public static function h6(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('h6', ...$value);
    }
    public static function main(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('main', ...$value);
    }
    public static function nav(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('nav', ...$value);
    }
    public static function section(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('section', ...$value);
    }

    // --- Text Content ---
    public static function blockquote(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('blockquote', ...$value);
    }
    public static function dd(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('dd', ...$value);
    }
    public static function div(HtmlClass|string|null $class = null, Node|string ...$value): HtmlTag
    {
        $tag = HtmlTag::make('div', ...$value);
        if ($class) {
            $tag->addClass($class instanceof HtmlClass ? (string) $class : $class);
        }
        return $tag;
    }
    public static function dl(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('dl', ...$value);
    }
    public static function dt(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('dt', ...$value);
    }
    public static function figcaption(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('figcaption', ...$value);
    }
    public static function figure(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('figure', ...$value);
    }
    public static function hr(): HtmlTag
    {
        return HtmlTag::make('hr');
    }
    public static function li(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('li', ...$value);
    }
    public static function menu(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('menu', ...$value);
    }
    public static function ol(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('ol', ...$value);
    }
    public static function p(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('p', ...$value);
    }
    public static function pre(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('pre', ...$value);
    }
    public static function ul(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('ul', ...$value);
    }

    // --- Inline Text Semantics ---
    public static function a(string $uri, Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('a', ...$value)->setAttribute('href', $uri);
    }
    public static function abbr(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('abbr', ...$value);
    }
    public static function b(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('b', ...$value);
    }
    public static function bdi(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('bdi', ...$value);
    }
    public static function bdo(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('bdo', ...$value);
    }
    public static function br(): HtmlTag
    {
        return HtmlTag::make('br');
    }
    public static function cite(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('cite', ...$value);
    }
    public static function code(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('code', ...$value);
    }
    public static function data(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('data', ...$value);
    }
    public static function dfn(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('dfn', ...$value);
    }
    public static function em(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('em', ...$value);
    }
    public static function i(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('i', ...$value);
    }
    public static function kbd(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('kbd', ...$value);
    }
    public static function mark(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('mark', ...$value);
    }
    public static function q(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('q', ...$value);
    }
    public static function rp(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('rp', ...$value);
    }
    public static function rt(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('rt', ...$value);
    }
    public static function ruby(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('ruby', ...$value);
    }
    public static function s(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('s', ...$value);
    }
    public static function samp(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('samp', ...$value);
    }
    public static function small(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('small', ...$value);
    }
    public static function span(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('span', ...$value);
    }
    public static function strong(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('strong', ...$value);
    }
    public static function sub(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('sub', ...$value);
    }
    public static function sup(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('sup', ...$value);
    }
    public static function time(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('time', ...$value);
    }
    public static function u(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('u', ...$value);
    }
    public static function var(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('var', ...$value);
    }
    public static function wbr(): HtmlTag
    {
        return HtmlTag::make('wbr');
    }

    // --- Image and Multimedia ---
    public static function area(): HtmlTag
    {
        return HtmlTag::make('area');
    }
    public static function audio(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('audio', ...$value);
    }
    public static function img(string $src, ?string $alt = null, ?int $width = null, ?int $height = null): HtmlTag
    {
        $tag = HtmlTag::make('img')->setAttribute('src', $src);
        if ($alt !== null) {
            $tag->setAttribute('alt', $alt);
        }
        if ($width !== null) {
            $tag->setAttribute('width', (string) $width);
        }
        if ($height !== null) {
            $tag->setAttribute('height', (string) $height);
        }
        return $tag;
    }
    public static function map(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('map', ...$value);
    }
    public static function track(): HtmlTag
    {
        return HtmlTag::make('track');
    }
    public static function video(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('video', ...$value);
    }

    // --- Embedded Content ---
    public static function embed(string $src, ?string $type = null, ?int $width = null, ?int $height = null): HtmlTag
    {
        $tag = HtmlTag::make('embed')->setAttribute('src', $src);
        if ($type !== null) {
            $tag->setAttribute('type', $type);
        }
        if ($width !== null) {
            $tag->setAttribute('width', (string) $width);
        }
        if ($height !== null) {
            $tag->setAttribute('height', (string) $height);
        }
        return $tag;
    }
    public static function iframe(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('iframe', ...$value);
    }
    public static function object(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('object', ...$value);
    }
    public static function picture(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('picture', ...$value);
    }
    public static function portal(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('portal', ...$value);
    }
    public static function source(string $src, ?string $type = null): HtmlTag
    {
        $tag = HtmlTag::make('source')->setAttribute('src', $src);
        if ($type !== null) {
            $tag->setAttribute('type', $type);
        }
        return $tag;
    }

    // --- Scripting ---
    public static function noscript(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('noscript', ...$value);
    }
    public static function script(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('script', ...$value);
    }

    // --- Demarcating Edits ---
    public static function del(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('del', ...$value);
    }
    public static function ins(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('ins', ...$value);
    }

    // --- Table Content ---
    public static function caption(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('caption', ...$value);
    }
    public static function col(): HtmlTag
    {
        return HtmlTag::make('col');
    }
    public static function colgroup(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('colgroup', ...$value);
    }
    public static function table(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('table', ...$value);
    }
    public static function tbody(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('tbody', ...$value);
    }
    public static function td(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('td', ...$value);
    }
    public static function tfoot(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('tfoot', ...$value);
    }
    public static function th(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('th', ...$value);
    }
    public static function thead(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('thead', ...$value);
    }
    public static function tr(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('tr', ...$value);
    }

    // --- Forms ---
    public static function button(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('button', ...$value);
    }
    public static function datalist(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('datalist', ...$value);
    }
    public static function fieldset(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('fieldset', ...$value);
    }
    public static function form(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('form', ...$value);
    }
    public static function input(string $type = 'text'): HtmlTag
    {
        return HtmlTag::make('input')->setAttribute('type', $type);
    }
    public static function label(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('label', ...$value);
    }
    public static function legend(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('legend', ...$value);
    }
    public static function meter(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('meter', ...$value);
    }
    public static function optgroup(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('optgroup', ...$value);
    }
    public static function option(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('option', ...$value);
    }
    public static function output(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('output', ...$value);
    }
    public static function progress(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('progress', ...$value);
    }
    public static function select(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('select', ...$value);
    }
    public static function textarea(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('textarea', ...$value);
    }

    // --- Interactive Elements ---
    public static function details(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('details', ...$value);
    }
    public static function dialog(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('dialog', ...$value);
    }
    public static function summary(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('summary', ...$value);
    }

    // --- Web Components ---
    public static function slot(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('slot', ...$value);
    }
    public static function template(Node|string ...$value): HtmlTag
    {
        return HtmlTag::make('template', ...$value);
    }
}
