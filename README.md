# Vagrant + Puppet + Varnish ESI

Playing around with Varnish and ESI to see how I could best cache an
authenticated site with many moving parts that have different TTLs.

## Setup

    cd /path/to/vagrant-puppet-varnish-esi
    bundle
    bundle exec vagrant up

Now visit [http://192.168.34.101:6081/](http://192.168.34.101:6081/) in your
browser.
