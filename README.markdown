# Field: Wasabi S3 File Upload

This extension functions as a basic replacement for file uploads, allowing hosting on Wasabi Hot Cloud Storage (S3 Compatible) (it requires an [Wasabi account](http://wasabi.com/)). Uploaded files are world readable. It is not considered feature-complete; there is much additional functionality that could be added . If you have input, please contact us at the [Symphony forum](https://getsymphony.com).


## Installation

1. Upload `/wasabi_s3upload_field` to your Symphony `/extensions` folder.
2. Enable it by selecting the "Field: Wasabi S3 Upload", choose Enable from the with-selected menu, then click Apply.
3. Under Preferences, add your S3 Access Key ID and Secret Access Key.
4. You can now add the "Wasabi S3 File Upload" field to your sections. Select the bucket you wish to store files in from the dropdown.

## Origin

This extension is a variation of the 'Unique File Upload Field' extension by Michael Eichelsdoerfer and the Akismet extension (for System Preferences) by  Alistair Kerney. It uses the [Amazon S3 PHP class](http://undesigned.org.za/2007/10/22/amazon-s3-php-class) written by Donovan Schonknecht. This extension was started by Brian Zerangue, taken over by Andrew Shooner, and some slight modifications to get it working with Symphony 2.2 were made by Scott Tesoriere.
