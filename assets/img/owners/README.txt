Place owner images in this folder and store the filename (or full path/URL) in the database field `owner_image`.

Recommended filename: owner.jpg

If you uploaded an image via the web UI: ensure file permissions allow the web server to read it.

Example DB value (filename): owner.jpg
Example DB value (absolute path): /assets/img/owners/owner.jpg

To move the attached image into this folder on Windows (PowerShell):

# Save the attached image from your browser to Downloads, then run:
# Replace <downloaded-file> with the actual filename
Move-Item -Path "$env:USERPROFILE\Downloads\<downloaded-file>" -Destination "C:\wamp64\www\noir-spoon\assets\img\owners\owner.jpg"

Or upload via your IDE / file manager to: assets/img/owners/owner.jpg
