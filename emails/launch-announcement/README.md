# Crossroads Commons — Launch Announcement Email

A custom-coded, brand-aligned HTML email ready to paste into MailerLite. All images and the brand color bar are served directly from **crossroadscommons.com**, so there's nothing to upload — just copy, paste, and send.

---

## How to send this email

1. Open `index.html` in any text editor
2. Select all (⌘A) and copy (⌘C)
3. In MailerLite: **Campaigns → Create → Custom HTML** (paste in code)
4. Paste the HTML
5. Preview, send yourself a test, then schedule the send

That's it. No image uploads, no asset management.

---

## What's hosted on the website

All images referenced by the email live on crossroadscommons.com and load automatically:

| What | URL |
|------|-----|
| Logo (header + footer) | `/wp-content/themes/crossroads-commons/assets/images/email/cc-logo-horizontal-email.png` |
| Hero photo | `/wp-content/uploads/2026/03/LevelFields_Birds_Main-Drive_AFTER-DAY.jpg` |
| Script "A letter from our board" | `/wp-content/themes/crossroads-commons/assets/images/email/eyebrow-script.png` |
| Script "With gratitude," | `/wp-content/themes/crossroads-commons/assets/images/email/signature-script.png` |

The five-color brand bar (purple / sky blue / magenta / orange / yellow) is **pure HTML** — no image required. It's recreated with a table of colored cells, matching the `.color-bar` pattern used on the live site.

> **Deployment note:** the three files under `/assets/images/email/` are new. They're committed to the theme at `wp-content/themes/crossroads-commons/assets/images/email/` and will go live the next time the theme is deployed (GitHub Actions → DreamHost SFTP). If the images look broken when you first paste the HTML into MailerLite, that just means the theme hasn't deployed yet — merge to `main` and they'll appear.

---

## Swapping the hero photo

The hero currently uses the live "aerial vision" render from the site. To swap:

1. Upload a new image to WordPress (Media → Add New)
2. Copy its URL
3. In `index.html`, find this line:
   ```html
   <img src="https://crossroadscommons.com/wp-content/uploads/2026/03/LevelFields_Birds_Main-Drive_AFTER-DAY.jpg"
   ```
4. Replace the URL, save, re-paste into MailerLite

Recommended: 1200×680 JPEG (renders at 600×340 on desktop, scales to full width on mobile).

---

## Editing copy

All body text lives inline in `index.html`. Search for the headline you want to edit and update the text. Structure preserved:

- **Dear Friends,** — opening
- **Our Vision** — section
- **Introducing Our New Name & Website** — section
- **Construction Is Beginning** — section
- **With gratitude,** / **The Board of The Crossroads Renewal Project** — sign-off
- **Visit Our Website** — CTA button

Keep the section structure intact so the color-bar dividers sit between blocks.

---

## Unsubscribe

The footer uses MailerLite's merge tag `{$unsubscribe}` — it's automatically replaced with a working unsubscribe link when the campaign sends. Don't hardcode a URL in its place.

---

## Rendering notes

- **Plus Jakarta Sans** loads from Google Fonts. Apple Mail, iOS Mail, and Gmail iOS app will render it. Outlook and Gmail desktop fall back to Helvetica/Arial — still clean, still on-brand.
- **Script lettering** ("A letter from our board", "With gratitude,") is rendered as PNG from the licensed Native Record Script font — so it looks identical in every client.
- **Button** uses the bulletproof VML technique for Outlook — renders as a real purple rounded button everywhere.
- **Color bar** uses table cells with `bgcolor` attributes, which work in every client including Outlook.
- **No JavaScript, no embedded fonts, no background images on `<body>`** — all email-client best practices.

---

## Testing checklist

Before you send to the list:

- [ ] Sent a test to yourself and opened it in at least one desktop client (Apple Mail, Gmail web, or Outlook)
- [ ] Sent a test to yourself and opened it on your phone
- [ ] All images loaded (if not, the theme hasn't deployed — see deployment note above)
- [ ] The "Visit Our Website" button links to the homepage
- [ ] The email and unsubscribe links in the footer work
- [ ] Preheader text ("Greetings from the Board...") shows up in the inbox preview

---

## File map

```
emails/launch-announcement/
├── index.html        ← paste this into MailerLite
└── README.md         ← this file
```

Theme assets used (already committed):
```
wp-content/themes/crossroads-commons/assets/images/email/
├── cc-logo-horizontal-email.png
├── eyebrow-script.png
└── signature-script.png
```
