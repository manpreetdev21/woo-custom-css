# Woo Custom SASS/CSS

WooCommerce layout and style editor for custom themes. Adds a **Custom CSS** tab under
*WooCommerce → Settings* with sections for shop/category styling, layout, cart, checkout,
theme hooks and free-form CSS.

Settings live at `WooCommerce → Settings → Custom CSS`.

## How styling works

Every setting maps to a **CSS custom property**. The stylesheet
(`assets/frontend/css/woocommerce.css`) is plain CSS and ships with a modern default for
each property, so the plugin produces a complete, contemporary WooCommerce look with
**zero settings saved**. Saving a setting emits only that one property into a `:root{}`
block injected ahead of the stylesheet.

```
option  ──▶  WOOCUSTOMOPTION (read + sanitise)
        ──▶  WOOCSSVARIABLE  (build :root{} block)
        ──▶  wp_add_inline_style( 'woocustommincss', … )
        ──▶  woocommerce.css  var(--woo-x, <modern default>)
```

Nothing is compiled and nothing is written to disk at runtime.

### Styling from a theme

The stylesheet's own tokens are overridable, so a theme can restyle the plugin without
touching the settings screen:

```css
:root {
	--woo-accent: #7c3aed;
	--woo-radius: 4px;
	--woo-card-border: 1px solid #e5e7eb;
}
```

Or filter the generated properties in PHP:

```php
add_filter( 'woocustomcss_css_variables', function ( $properties ) {
	$properties['--woo-sale-bg'] = '#111827';
	return $properties;
} );
```

## Sections

Sections are ordered the way a theme gets built: global foundations, then structure, then
per-page styling in the order a customer walks the store, then behaviour, then raw CSS.

| Section | What it controls |
| --- | --- |
| General | Theme support and gallery features; global font, text/muted/link colours, surface and border colours, corner radius, transition speed, content width and gutter |
| Layout | Products per row, grid gap, image aspect ratio, card hover lift, accent colour |
| Product/Categories Page | Typography, colours, padding, borders and shadows for the category and product cards; card backgrounds and alignment; image fit; sale badge colours and position; star rating colour; product tabs and gallery radius; button transform, tracking and border |
| Cart Page | Table borders, radius, heading size/casing, row and text colours, cell padding, thumbnail width, remove-button colours, totals panel and cart heading |
| Checkout Page | Field border/background/text/focus colours, radius, height, label colour and weight; panel background, border, radius and padding; full-width place-order button |
| Theme Hooks | Breadcrumb, sidebar, related products, Gutenberg for products, wrapper HTML, add-to-cart button text, products per page, and toggles to hide the result count, sorting, sale badge, loop rating/price/add-to-cart, SKU, product meta, upsells, cross-sells, product tabs, coupons and order notes; related/gallery counts; WooCommerce stylesheet and cart-fragment control |
| Extra CSS | Free-form CSS — global, tablet and mobile editors, with the CodeMirror bundled in WordPress |

### Extra CSS

Three editors. The global one applies everywhere; the tablet and mobile ones are wrapped
in `@media (max-width: 991.98px)` and `@media (max-width: 575.98px)` automatically, so
write plain rules with no media query. They are emitted global → tablet → mobile, so the
narrower block wins on equal specificity. Breakpoints match the stylesheet's.

A block whose braces do not balance is dropped rather than printed — an unclosed `{`
would otherwise swallow every rule after it. Override design tokens from here too:

```css
:root { --woo-accent: #7c3aed; --woo-sale-bg: #111827; }
```

## Markup support

WooCommerce catalogue cards come in three shapes, and the plugin covers all three:

1. **Classic** — `ul.products > li.product` (WooCommerce's own `content-product.php`)
2. **Theme override** — a theme's own card markup, e.g. `div.card-product`
3. **Product Collection block** — `ul.wc-block-product-template > li.wc-block-product`

Shapes 2 and 3 fire none of the classic loop hooks, so:

- the "hide X" toggles apply the template unhook **and** a body-class CSS fallback
- the image hover swap collects gallery URLs on `the_post` (which all three run through)
  and overlays a second image via JS, rather than re-rendering the thumbnail — which used
  to duplicate the image on block shops

## Tests

```
php tests/test-css-vars.php
```

Covers the option → custom-property pipeline: unset options must emit nothing (so the
stylesheet fallback wins), unit/border validation, responsive column clamping, custom-CSS
assembly and brace balancing, and that a hostile option value cannot break out of the
`<style>` block.

## Requirements

- PHP 7.4+
- WordPress 5.0+
- WooCommerce 7.0+

## Notes on the 2.0.0 rewrite

The runtime SCSS compile was removed. It ran `scssphp` on *every* request — front end and
admin — and wrote two files to disk each time, and it produced an empty stylesheet on a
fresh install because the mixins referenced variables that `_variables_static.scss` never
defined. CSS custom properties do the same substitution natively.

`assets/frontend/sass/` and the vendored `include/composer/` (scssphp) were removed with
it — 80 files, ~820K. The plugin no longer has any third-party dependency, and no build
step: `assets/frontend/css/woocommerce.css` is edited directly.
