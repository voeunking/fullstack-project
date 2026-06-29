# TODO

## Login/Register UI redesign (Facebook + Google)

- [x] Inspect existing login/register UI entry points (Blade + Vue) and current styling approach (Tailwind/main.css).
- [ ] Create shared authentication panel styles (cards, inputs, buttons, social buttons, divider).
- [ ] Update admin login Blade design to match new Auth UI.
- [ ] Add public login/register Blade pages (with social buttons) if they don’t exist.
- [ ] If frontend Vue has auth screens, update them; otherwise keep to Blade.
- [ ] Wire social buttons to existing OAuth flow endpoints OR implement placeholders that post to backend routes.
- [ ] Keep layout responsive + accessible (focus states, aria labels, keyboard navigation).
- [ ] Run frontend build / Laravel checks (as applicable) to ensure no broken imports or template errors.


