# -*- mode: ruby -*-
# vi: set ft=ruby :

def define_vm config, role, index, ip, memory = 512
  id = (index + 1).to_s.rjust(3, '0')
  config.vm.define "#{role}_#{id}" do |box|
    box.vm.customize [ "modifyvm", :id, "--memory", memory ]
    box.vm.box = "centos_6_3"
    box.vm.box_url = "https://dl.dropbox.com/u/7225008/Vagrant/CentOS-6.3-x86_64-minimal.box"
    box.vm.network :hostonly, "192.168.34.#{ip}", :netmask => "255.255.255.0"
    box.vm.host_name = "#{role.downcase.gsub(/[^a-z0-9]+/, '-')}-#{id}.esi.dev"
    #box.vm.provision :shell, :path => "script/bootstrap-vm.sh"
    box.vm.provision :puppet, :module_path => "modules" do |p|
      p.manifests_path = "manifests"
      p.manifest_file  = "site.pp"
    end
  end
end

roles = {
  'cache' => 101..101,
  'app'   => 111..111,
}

Vagrant::Config.run do |config|
  roles.each do |name, range|
    range.to_a.each do |n|
      define_vm config, name, n - range.first, n
    end
  end
end
