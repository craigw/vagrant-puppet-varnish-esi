backend default {
  .host = "192.168.34.111";
  .port = "80";
}

sub vcl_fetch {
  if(req.url == "/") {
    set beresp.do_esi = true;
  }
}
