====PHPdocX 2.5 by 2mdc.com====
http://www.phpdocx.com/

PHPDOCX is a PHP library designed to dynamically generate reports in Word format (WordprocessingML).

The reports may be built from any available data source like a MySQL database or a spreadsheet. The resulting documents remain fully editable in Microsoft Word (or any other compatible software like Open Office) and therefore the final users are able to modify them as necessary.

The formatting capabilities of the library allow the programmers to generate dynamically and programmatically all the standard rich formatting of a typical word processor.

This library also provides an easy method to generate documents in other standard formats such as PDF or HTML.

====What's new on PHPdocX 2.5?====
PRO VERSION
This new version of PHPDocX includes several enhancements that will greatly simplify the generation of Word documents with PHP.The main improvements can be summarized as follows:¥ New embedHTML method that:	o Directly translates HTML into WordProcessingXML.	o Allows to use native Word Styles, i.e. we may require that the HTML tables are formatted following a standard Word table style.	o Is compatible with OpenOffice and the Word 2003 compatibility pack.	o May download external HTML pages (complete or selected portions) embedding their images into the Word document.

¥ PHPDocX v2.5 now uses base templates that allow:	o To use all standard Word styles for:		¥ Paragraphs.		¥ Tables with special formatting for first and last rows and columns, banded rows and columns and another standard features.		¥ Lists with several different numbering styles.		¥ Footnotes and endnotes.	o Include standard headings (numbered or not).	o Include customized headers and footers as well as front pages.
¥ There are new methods that allow you to parse all the available styles of a Word document and import them into your base template:	o parseStyles  generates a Word document with all the available styles as well as the required PHPDocX code to use them in your final Word document (you may download here the result of this method applied to the default PHPDocX base template).	o importStyles allows to integrate new styles  extracted from an external Word document into your base template.

¥ New conversion plugin (based on OpenOffice) that improves the generation of PDFs, RTFs and legacy versions of Word documents.
¥ New standardized page layout properties (A4, A3, letter, legal and portrait/landscape modes) trough the new modifyPageLayout method.
¥ The addTemplate method has been upgraded to greatly improve its performance.
¥ You may directly import sophisticated headers and footers from an existing Word document with the new  importHeadersAndFooters method.
As well as many other minor fixes and improvements.We have also upgraded our documentation section by simplifying the access to the available library examples and we have included a tutorial that will help newcomers to get grasp of the power of PHPDocX.

====What are the minimum technical requirements?====
To run PHPDocX you need to have a functional LAMP setup, this should include:

- PHP5
- Required : Support ZipArchive and XSLT
- A data source such as a MySQL database, an Excel spreadsheet or an CSV file.
- Apache 2.x +
- A functional version of Word 2007 or newer is required to generate legacy versions (Word 97 - 2004 .doc documents)

====LICENSES====
PhpdocX is available under four different licenses:

- Free. LGPL licensed (http://www.gnu.org/licenses/lgpl.html): this is the most economical way to access PHPdocX and allows you to try many of its functions without limitations. It can be downloaded for free from http://www.phpdocx.com

- Pro: Priced at USD 90 (EUR 73), PhpdocX Pro version adds many customizable features.

- Pro (on Site). This version includes the installation of the library on your server, custom templates and maintenance for one year. Request a quote for this option.

- Pro (on Site - Multisite). This version includes custom templates, maintenance for one year and installation on multiple sites. Request a quote for this option.

====What are the main differences between PHPdocX Free and PHPdocX Pro?====
PHPdocX Free allows you to dynamically generate docx files with simple formating options such as lists, page numbering and tables, no watermarks are embedded and there is no trail period or limit in the amount of documents you can generate. PHPdocX Pro extends this functionality and allows you to include nested lists, text boxes, customized headers and footers, 3D graphs, MathML features and it also allows you to create Word 97 - 2004 .doc documents.

You can access a table comparing the features of the free version and the Pro version at: http://www.phpdocx.com/features

You can download the Free, LGPL version or the Pro Version from http://www.phpdocx.com/

PHPDocX is developed by 2mdc (http://www.2mdc.com).