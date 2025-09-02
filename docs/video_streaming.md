Video streaming (fast delivery with webserver offload)
===============================================

This document explains a safe, opt-in way to let your web server (nginx or Apache)
serve large local video files while keeping Laravel in front for URL authorization
and lightweight control. The repository includes example config snippets under
`docs/nginx/protected_videos.conf` and `docs/apache/xsendfile.conf`.

Quick summary
-------------
- Default (no change): Laravel will stream files using PHP (works everywhere).
- Recommended (opt-in): Configure your web server to serve files directly via
  X-Accel-Redirect (nginx) or X-Sendfile (Apache). This greatly reduces PHP CPU
  and memory and increases throughput.

Why this helps
---------------
- PHP streaming opens the file and pushes bytes through PHP. That consumes PHP
  workers and memory for each connection.
- Offloading to nginx/Apache moves byte delivery to the webserver which is
  optimized for static file delivery and uses kernel-level file serving.

How to enable (nginx)
----------------------
1. Pick the absolute path on your server where the video files live. Example:

   /full/path/to/your/project/storage/app/videos/

2. Add the internal location to your nginx site config (or include the file
   `docs/nginx/protected_videos.conf`):

   - Create a file (or include) in your nginx site config, for example:
     ```nginx
     include /path/to/your/repo/docs/nginx/protected_videos.conf;
     ```

   - Or copy the contents of `docs/nginx/protected_videos.conf` into your site
     config inside the appropriate `server { }` block.

3. Restart nginx:

   sudo systemctl restart nginx

4. Enable the opt-in behavior in Laravel by updating `.env`:

   VIDEO_ACCEL_REDIRECT=true
   VIDEO_ACCEL_REDIRECT_DRIVER=nginx

5. Test the endpoint in your browser or with curl. The controller will return an
   `X-Accel-Redirect` header pointing to an internal location and nginx will
   serve the file efficiently.

How to enable (Apache + X-Sendfile)
----------------------------------
1. Ensure `mod_xsendfile` is installed and enabled. On some systems:

   sudo apt-get install libapache2-mod-xsendfile
   sudo a2enmod xsendfile

2. In your virtual host config enable XSendFile and set the allowed path:

   Include the example `docs/apache/xsendfile.conf` inside your vhost or copy
   the relevant lines.

3. Restart Apache and enable the env vars:

   VIDEO_ACCEL_REDIRECT=true
   VIDEO_ACCEL_REDIRECT_DRIVER=apache

Security notes
--------------
- The repository controller ships with an opt-in flag so behavior does not
  change unless you set the `.env` variables.
- Keep the server `internal` location (nginx) or XSendFilePath restricted so
  files cannot be served directly from public URL paths.
- For production, consider coupling this with authenticated routes or
  short-lived signed URLs to avoid public access.

Fallback and testing
---------------------
- If `VIDEO_ACCEL_REDIRECT` is not enabled, Laravel will continue streaming
  from PHP (no change).
- After enabling, use `curl -I 'https://your-host/videos/stream?file=1.mp4'`
  to confirm responses and inspect headers.

Files included in this repo
---------------------------
- `docs/nginx/protected_videos.conf` - example nginx internal location snippet
- `docs/apache/xsendfile.conf` - example apache X-Sendfile snippet

If you want, I can add a short test script to verify the nginx header behavior on
your machine. Otherwise follow the steps above and test with curl to validate.
