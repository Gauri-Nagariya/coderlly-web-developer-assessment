# Nexora Web Development Assessment

## Overview

This submission contains two parts:

-   **Page 1 --- Nexora landing page:** a standalone HTML, CSS, and
    vanilla JavaScript page.
-   **Page 2 --- Request a Demo:** a WordPress page that uses the custom
    **Nexora Demo Request** plugin to display a form and save
    submissions in WordPress.

## Prerequisites

### Page 1

-   A web browser.
-   Visual Studio Code or another editor.
-   The VS Code **Live Server** extension, or an equivalent local
    static-file server.

### Page 2

-   A working local WordPress installation.
-   Access to the WordPress site's files, including its
    `wp-content/plugins/` directory.
-   A WordPress administrator account to activate the plugin, create the
    page, and view saved requests.

**Local WordPress environment:** The site is currently accessed at
`http://nexora-demo.local/`.\

------------------------------------------------------------------------

## Page 1: Run the landing page

### Files

The landing page uses:

-   `index.html`
-   `style.css`
-   `script.js`

Keep these files together in the `page1-landing/` directory because the
HTML references `style.css` and `script.js` by relative path.

### Run locally

1.  Open the project folder in Visual Studio Code.

2.  Confirm that `index.html`, `style.css`, and `script.js` are in the
    same directory.

3.  Open `index.html` and choose **Open with Live Server**.

4.  Visit the local URL:

    `http://127.0.0.1:5500/page1-landing/index.html`

    If Live Server opens a different URL on your computer, use the URL
    displayed in your browser.

### Page 1 functionality

-   Responsive navigation menu for smaller screens.
-   Navigation closes when a navigation link is selected or the Escape
    key is pressed.
-   FAQ accordion controlled by buttons.
-   Contact form validation for name, email, and message.
-   Inline validation messages and a success confirmation after valid
    input.
-   Keyboard focus styling and a skip link to the main content.

**Important:** The Page 1 contact form currently validates input in the
browser and displays a confirmation message. The supplied JavaScript
does not send the contact form data to a server or save it in a
database.

------------------------------------------------------------------------

## Page 2: WordPress Request a Demo page

### Plugin files

The custom plugin folder should be named:

``` text
nexora-demo-request/
├── nexora-demo-request.php
└── assets/
    └── style.css
```

The PHP file uses the stylesheet path `assets/style.css`.


### Install and activate the plugin

1.  Open the local WordPress site's files.

2.  Copy the complete `nexora-demo-request/` folder into:

    `wp-content/plugins/`

3.  Sign in to the WordPress dashboard:

    `http://nexora-demo.local/wp-admin/`

4.  Open **Plugins → Installed Plugins**.

5.  Find **Nexora Demo Request** and click **Activate**.


### Create the Request a Demo page

1.  In the WordPress dashboard, go to **Pages → Add New Page**.

2.  Set the page title to **Request a Demo**.

3.  Add the following shortcode to the page content:

    ``` text
    [nexora_demo_form]
    ```

4.  Publish the page.

5.  Open the page and confirm that the form and its styling display
    correctly.

The configured local page URL is:

`http://nexora-demo.local/request-a-demo/`

The page slug is `request-a-demo`.


### Form behavior

The custom plugin form includes:

-   Full name
-   Work email
-   Company
-   Message describing what the visitor would like to see

The plugin checks the WordPress nonce, sanitizes submitted values,
validates the fields, and saves successful submissions as the custom
post type **Demo Requests**. It displays a confirmation after a
successful submission and validation or error messages when applicable.

The supplied code requires a name, valid email, company name, and a
message between 10 and 2,000 characters. The name is limited to 100
characters.

### Testing status

The Request a Demo form has been tested successfully. The form submission should also be verified in the WordPress **Dashboard → Demo Requests** area to confirm that the submitted data is saved and accessible to the administrator.

### View saved demo requests

1.  Sign in to the WordPress dashboard using an account with permission
    to access the admin area.
2.  In the dashboard sidebar, open **Demo Requests**.
3.  Select a request from the list to open its edit screen.
4.  Review the **Request details** panel for the name, email, company,
    and message. The list also includes Email, Company, and Submitted
    columns.

The plugin stores the requests in WordPress as entries of the
`nexora_demo_request` custom post type, with the submitted fields stored
as post metadata.

------------------------------------------------------------------------

## Design choices

### Page 1

The landing page uses a clean software-product style with a navy, blue,
teal, white, and warm off-white palette. Rounded cards and buttons,
generous spacing, a large hero heading, feature cards, statistics, FAQs,
and a contact form organize the content. The page uses the Inter font
and includes responsive layout rules, visible keyboard focus styles, and
a skip link.

### Page 2

The Request a Demo form follows the same visual direction through navy
and blue accents, a white rounded form panel, warm off-white input
backgrounds, clear field labels, and visible error and success messages.
Its stylesheet is scoped under `.nexoraDemoReq` to limit unintended
styling effects on other WordPress content.

------------------------------------------------------------------------

## Hostinger VPS Deployment Plan

The project currently runs locally. The following steps describe the planned deployment process; the project has **not been deployed to a production VPS**.

1. **Prepare the VPS:** Configure a compatible web server, PHP, database service, and WordPress environment.
2. **Upload project files:** Upload the landing page files to the appropriate web directory and install the `nexora-demo-request` plugin in the WordPress installation's `wp-content/plugins/` directory.
3. **Configure WordPress and the database:** Create or configure the production database. If migrating the local WordPress site, back up and import its database and update the site configuration and URLs as needed.
4. **Configure the domain and HTTPS:** Point the domain to the VPS, configure the web server, and enable HTTPS using a valid SSL/TLS certificate.
5. **Activate the plugin:** Activate **Nexora Demo Request** in WordPress and ensure the Request a Demo page contains the `[nexora_demo_form]` shortcode.
6. **Test the deployment:** Verify both pages on desktop and mobile. Test valid and invalid form submissions and confirm that successful requests are saved under **Dashboard → Demo Requests**.
7. **Configure backups:** Schedule regular backups of website files and the database, preferably keeping an additional copy off the VPS.

**Deployment status:** Not deployed. Production configuration and credentials would be provided and verified during the actual deployment.

------------------------------------------------------------------------

## Screenshots

The following screenshots were captured from the locally running pages and are included in the `screenshots/` directory:

* `page1-desktop.png` — Landing page at desktop size.
* `page1-mobile.png` — Landing page at mobile size.
* `page1-form.png` — Page 1 contact form.
* `page2-desktop.png` — page 2 at desktop size.
* `page2-mobile.png` — page 2 at mobile size.
* `page2-form.png` — Page 2 demo request form.

------------------------------------------------------------------------

## Time spent

Approximately **2 days**.

------------------------------------------------------------------------

## Project Structure

```text
coderlly-assessment/
├── page1-landing/
│   ├── index.html
│   ├── style.css
│   └── script.js
├── wordpress-plugin/
│   └── nexora-demo-request/
│       ├── nexora-demo-request.php
│       └── assets/
│           └── style.css
├── screenshots/
│   ├── page1-desktop.png
│   ├── page1-mobile.png
│   ├── page1-form.png
│   ├── page2-desktop.png
│   ├── page2-mobile.png
│   └── page2-form.png
└── README.md
```
