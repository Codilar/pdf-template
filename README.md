## HTML PDF Generator

This module enables the Admin to easily modify the Invoice, Shipment, and other PDF templates using HTML, right from the Admin Panel!

### Installation
`composer require codilar/pdf-template`

### Setup
- Once the module is installed, go to `Stores` > `Configuration` > `Sales` > `PDF Print-outs`
  and under the _Invoice_ and _Shipment_ section, set the _Use HTML PDF template_ to Yes
- Go to `Content` > `PDF Template`  and choose either the `Invoice Template`, or `Shipment Template` whichever you want to edit
- Choose the store you want to edit for
- Modify the template (you can see the changes in real-time on the preview section
- Save, and we're done!

Now whenever a PDF is requested (in Admin panel, email, etc) Magento will use our newly designed HTML template to generate the PDF.

### Templating
The module uses the [Handlebars](https://handlebarsjs.com/guide/) templating engine for designing the templates. For a tutorial on how to get started, head over to `Content` > `PDF Template` > `Tutorial` section.
