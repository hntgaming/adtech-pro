# Lessons Learned

## 2026-02-07

- Date: 2026-02-07
- Mistake: Invalid CSS properties shipped in production (`will-change: contents`, `min-height: attr(style)`)
- Root cause: CSS authored without validating against spec. `will-change` only accepts `auto`, `scroll-position`, or CSS property names (e.g., `transform`, `opacity`). `attr()` is only supported for `content` property in current browsers.
- Prevention rule: Before adding any `will-change` or `attr()` CSS, verify against MDN spec. Only use `will-change` with concrete animatable properties.
- Checklist to run next time:
  1. Validate all `will-change` values against: auto | scroll-position | <custom-ident>
  2. Never use `attr()` outside of `content:` until CSS Values Level 5 ships
  3. Run CSS linter (stylelint) on new CSS before commit

---

- Date: 2026-02-07
- Mistake: Admin form handlers had nonce checks but missing `current_user_can()` capability checks
- Root cause: Relied on nonce alone for authorization. Nonces verify intent, not capability.
- Prevention rule: Every WordPress form save handler must have BOTH `check_admin_referer()` AND `current_user_can()` before processing any data.
- Checklist to run next time:
  1. Search for all `check_admin_referer` calls
  2. Verify each has a paired `current_user_can()` check
  3. Verify capability matches the page's `add_menu_page` / `add_submenu_page` capability argument
