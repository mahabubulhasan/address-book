address-book
============

Small mvc application demonstrating basic functionality of zend framework 2

<b>Instruction to run this code:</b>

To run this project you don't need to create any virtual host.
This code doesn't contain the Zend Framework 2 library inside it, so you need to download the Zend Framework 2 library and then copy the library directory inside <code>vendor/ZF2/</code> directory.


<b>Database Table</b>

<pre>
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
</pre>

<b>NOTE:</b> This source doesn't contain the Zend Framework 2 library, please download library from here <a href="https://github.com/zendframework/zf2">https://github.com/zendframework/zf2</a> then copy the <code>library</code> directory and paste it inside the <code>vendor/ZF2/</code> directory.

<b>Alternative way to create project</b>
If you failed to run this code for some unknown reason, please follow the instructions in <a href="wp.me/pfBBw-cA">here</a>.
