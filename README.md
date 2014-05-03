wp-facebook-link-preview
========================

Facebook Link Preview for Wordpress


Provides an API to get details from an URL. 

Title / Images and Description to output a link preview just like Facebook Link Share.

Done

1. Get Title / Images and Description via Open Graph meta
2. If no Open Graph found, get Title and Images and Description from page.
3. Output results to JSON, also verify URL is Valid, and return status OK if everything OK, or status Url Invalid if URL is not Valid.

To-do

4. Create a hook for logged-in / non-logged-in users to get the details of the URL.
5. Create a hook to save details for URL.
6. Attach a hook to comments to auto-load preview if URL four in content of a comment.
