# Coder Metabox for WordPress
 Coder Metabox for WordPress - Create Pages, Posts Custom Meta Fields options.

## Step 1
call coder-metabox.php file in functions.php
```
require_once('coder-metabox.php');
```

## Step 2
Create New Meta Options Box
```
$box = new CoderMetaBox();
$box->id ="post_option_1";
$box->type ="post";
$box->name ="Post Options";
```

## Step 3
Create New Fields 
```
$prefix = 'cits_';
$box->coder_meta_fields = array(
    array(
        'label'=> 'Name',
        'desc'  => 'Here type your name.',
        'id'    => $prefix.'customer_name',
        'type'  => 'text'
    ),
    array(
        'label'=> 'About Customer',
        'desc'  => 'Type about the Customer.',
        'id'    => $prefix.'about_customer',
        'type'  => 'textarea'
    ),
);

```

## Step 4
******* Output ******
```
the_coder_field('field__id');
get_coder_field('field__id');
```

Enjoy 🥳
