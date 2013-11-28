Bruce's Vintage Watches
=======================

This is a pro bono website re-write project built in CakePHP.

The Cake Config folder is submodule kept in a private repository to keep
senstive passwords, etc out of the public github repo.

If you are collaborating with me set up the project as follows:

<ol>
<li>`git clone git@github.com:kriskd/vintagewatches.git`</li>
<li>`git submodule init`</li>
<li>`git submodule update` There will be a password prompt to get this private repo that I will provide.</li>
<li>Get dependencies from Composer with `composer update`</li>
<li>Reference Gruntfile.js for the list of Grunt Plugins to install</li>
<li>In Plugin/Stripe/Vendor create this sym link if it doesn't exist
`ln -s ../../../Vendor/stripe/stripe-php Stripe`</li>
<li>Make tmp directory web writeable with `sudo chown -R www-data:www-data tmp/`</li>
<li>The full CakePHP project should live at the same level as this project in a folder named cakephp
with tag 2.4.2 checkout out</li>
</ol>

The previous version of the project https://github.com/kriskd/thewatchstore
will likely be going away.