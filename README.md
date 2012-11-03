# Vagrant + Puppet + CDH3

I want to play with Hadoop and the various tools built on top of it so I can
evaluate them for future project use. There's no easy way to do this at the
moment, and the instructions for getting a real distributed cluster up and
running are fairly lengthy - if I screw something up I don't want to rebuild
the entire thing from scratch.

Vagrant, and a set of Puppet modules and manifests, provide me an easily
setup throw-away Hadoop cluster which I can play with.

## Getting setup

    $ cd /path/to/clone/of/vagrant-puppet-cdh3
    $ bundle
    $ bundle exec vagrant up
    ... time passes, potentially quite a long time ...

You'll probably want to add the following entires to your `/etc/hosts` file,
but they're not completely necessary and both HDFS and MapReduce will continue
to work without them:

    192.168.33.101	primary-namenode-001.hadoop.dev	primary-namenode-001
    192.168.33.111	job-tracker-001.hadoop.dev	job-tracker-001
    192.168.33.121	datanode-001.hadoop.dev	datanode-001
    192.168.33.122	datanode-002.hadoop.dev	datanode-002
    192.168.33.123	datanode-003.hadoop.dev	datanode-003
    192.168.33.124	datanode-004.hadoop.dev	datanode-004
    192.168.33.125	datanode-005.hadoop.dev	datanode-005
    192.168.33.126	datanode-006.hadoop.dev	datanode-006

That's it, you should now have a functioning fully distributed Hadoop cluster
that'll run MapReduce jobs.


## Adding data to HDFS

To do anything useful with Hadoop you'll want to get some data into the
HDFS instance for the cluster. I have about 18 months of weather readings
taken every few hours from around 8000 aerodromes around the world so I've
used that to play with. Unfortunately I don't know what the licence for this
data is so I can't distribute it, you'll have to find your own data set to
play with. Sorry.

The `data` directory of the project is mounted in every VM as `/data`. Copy
your data files into this shared folder, then inside one VM - I used the
primary namenode - tell Hadoop to copy it into the HDFS:

    $ cd /usr/lib/hadoop-0.20
    $ ./bin/hadoop dfs -copyFromLocal /data input

I ran this as the `vagrant` user so the data ended up in a directory called
`/home/vagrant/input` in HDFS. Check it's there by asking Hadoop for a listing
of the directory:

    $ cd /usr/lib/hadoop-0.20
    $ ./bin/hadoop dfs -ls input

## Running a MapReduce Job

### A simple streaming example

Centos 6.3, which this cluster uses, has the `cat` and `wc `binaries at these
paths:

    /bin/cat
    /usr/bin/wc

We'll use those binaries to create a simple streaming MapReduce job that
counts all the characters, words and lines in your input data files.

Assuming that you imported the data as shown above, this is the command you'll
need whcih I again ran from inside the primary namenode:

    $ cd /usr/lib/hadoop-0.20
    $ sudo -u hdfs ./bin/hadoop jar \
        ./contrib/streaming/hadoop-streaming-0.20.2-cdh3u5.jar \
        -input '/user/vagrant/input/*' \
        -output my-mapreduce-output \
        -mapper /bin/cat \
        -reducer /usr/bin/wc

You should see a fairly large amount of logging output to the console
eventually followed by a success message which looks a little like this:

    12/10/28 17:04:05 INFO streaming.StreamJob: Job complete: job_201210281459_0006
    12/10/28 17:04:05 INFO streaming.StreamJob: Output: my-mapreduce-output

Note that this may be very slow, especially on large datasets, as you're
running a lot of VMs on the cores and disks that are on your local machine,
using the internal VirtualBox network.

Once your job has finished, you can do this on one of the VMs in the Hadoop
cluster to fetch the results:

    $ cd /usr/lib/hadoop-0.20
    $ sudo -u hdfs ./bin/hadoop dfs -get my-mapreduce-output ~/mapreduce-output
    $ cat ~/mapreduce-output/part-00000
    10810899 125060394 993090345

Turns out I have 10810899 lines worth of weather readings. Each line is one
reading, so I have a reasonable amount of data to play with. Ace.

Clearly I could do the above job very easily using `wc` on my local machine
without all the Hadoop setup used above, but this at least shows that the
MapReduce framework is up and running and can perform calculations. Now I can
use it for some real work!

## Authors

Craig R Webster <[http://barkingiguana.com/][0]>

[0]: http://barkingiguana.com/
