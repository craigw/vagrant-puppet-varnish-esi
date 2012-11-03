node base {
  exec { "disable iptables":
    command => "/sbin/iptables -F"
  }

  include hosts
}

node default inherits base {
  crit "$fqdn has not been configured"
}

node /^cache-\d{3}\.esi\.dev$/ inherits base {
  include varnish
  include sample_app::caching
}

node /^app-\d{3}\.esi\.dev$/ inherits base {
  include httpd
  include php
  include sample_app::website
}

class varnish {
  include varnish::install
  include varnish::configure
  include varnish::service
}

class hosts {
  host { "cache-001.esi.dev":
    ip => "192.168.34.101"
  }

  host { "cache-002.esi.dev":
    ip => "192.168.34.102"
  }

  host { "cache-003.esi.dev":
    ip => "192.168.34.103"
  }

  host { "web-001.esi.dev":
    ip => "192.168.34.111"
  }

  host { "web-002.esi.dev":
    ip => "192.168.34.112"
  }

  host { "web-003.esi.dev":
    ip => "192.168.34.113"
  }
}

class varnish::install {
  yumrepo { "varnish":
    baseurl  => "http://repo.varnish-cache.org/redhat/varnish-3.0/el6/x86_64/",
    descr    => "Varnish",
    enabled  => 1,
    priority => 1,
    gpgcheck => 0
  }

  package { "varnish":
    ensure => installed,
    require => Yumrepo["varnish"]
  }
}

class varnish::configure {
}

class varnish::service {
  service { "varnish":
    ensure => running,
    require => Class["varnish::configure"]
  }

  service { ["varnishlog", "varnishncsa"]:
    ensure => running,
    require => Service["varnish"]
  }
}

class httpd {
  include httpd::install
  include httpd::configure
  include httpd::service
}

class httpd::install {
  package { "httpd":
    ensure => installed
  }
}

class httpd::configure {
}

class httpd::service {
  service { "httpd":
    ensure => running
  }
}

class php {
  package { "php":
    ensure => installed
  }
}

class sample_app::caching {
  file { "/etc/varnish":
    recurse => true,
    source => "puppet:///modules/sample_app/varnish",
    notify => Service["varnish"],
    require => Package["varnish"]
  }
}

class sample_app::website {
  file { "/var/www/www.esi.dev":
    ensure => directory,
    require => Package["httpd"]
  }

  file { "/var/www/www.esi.dev/htdocs":
    recurse => true,
    owner => "apache",
    group => "apache",
    source => "puppet:///modules/sample_app/src",
    require => File["/var/www/www.esi.dev"]
  }

  file { "/etc/httpd/conf.d/vhost.conf":
    ensure => present,
    source => "puppet:///modules/sample_app/vhost.conf",
    require => Package["httpd"],
    notify => Service["httpd"]
  }
}
