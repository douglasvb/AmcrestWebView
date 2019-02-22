ToDo list:

- Look into waiting a second for an image to load with the wordpress HTML.  Might need to make a plugin or something to do that?  Maybe could do it via PHP with the way the image is returned from the other server?
- Try to find an image after a minute has passed if it didn't find one.
- Make a WordPress plugin for this because the way I'm making this work with WordPress is really not great
- Move configuration stuff into its own file
- Resize image options so I can serve a smaller resolution image for those that want it
- Verify that image is the correct size and if not, wait a bit and try again -- this is for when the script catches the camera uploading a new file before it's done.  Maybe another approach would be to only serve an image that's been on the server longer than X seconds?

