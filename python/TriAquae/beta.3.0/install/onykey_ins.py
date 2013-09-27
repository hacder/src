#!/usr/bin/env python
import os

def SoftIns(soft_list,sys_version,Ins_Dir):
    # First installation must install python
    if soft_list.count('python') == 1:
        print "/bin/bash python_ins.sh"
        os.system("/bin/bash python_ins.sh")
        soft_list.remove('python')

    for soft_name in soft_list:
        if sys_version == "redhat":
            if soft_name == "httpd":
                print "/bin/bash %s_ins.sh %s" % (soft_name,Ins_Dir)
                os.system("/bin/bash %s_ins.sh %s" % (soft_name,Ins_Dir))
            elif soft_name == "rrdtool":
                print "/bin/bash %s_ins.sh %s/rrdtool" % (soft_name,Ins_Dir)
                os.system("/bin/bash %s_ins.sh %s/rrdtool" % (soft_name,Ins_Dir))
            elif soft_name == "shellinabox":
                print "/bin/bash %s_ins.sh %s/shellinaboxd" % (soft_name,Ins_Dir)
                os.system("/bin/bash %s_ins.sh %s/shellinaboxd" % (soft_name,Ins_Dir))
            else:
                print "/bin/bash %s_ins.sh" % soft_name
                os.system("/bin/bash %s_ins.sh" % soft_name)

        elif sys_version == "ubuntu":
            print "apt-get install %s -y" % soft_name
            os.system("apt-get install %s -y" % soft_name)


def ModIns(mod_list):
    for mod_name in mod_list:
        if mod_name == "paramiko":
            print "pip install paramiko"
            os.system("/bin/bash gmp_ins.sh")
            os.system("pip install pycrypto >/dev/null 2>&1")
            os.system("pip install paramiko >/dev/null 2>&1")
	elif mod_name == "MySQL-python":
            if os.system("pip install %s >/dev/null 2>&1" % mod_name) != 0:
		os.system("yum install %s -y >/dev/null 2>&1" % mod_name)
	    
	else:
            print "pip install %s" % mod_name
            os.system("pip install %s >/dev/null 2>&1" % mod_name)


