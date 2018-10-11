<?php

include_once 'vendor/autoload.php';

use EUAutomation\GraphQL\Client;

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Unite CMS image resizing Example</title>
    </head>

<body>

<?php

# unite cms credentials
$unite_cms_endpoint = 'https://XXXX.unitecms.io/mydomain/api';
$unite_cms_api_token = 'XXXXXXXXX';

$query = '{
    findPage {
      result {
        headline,
        image {
          type,
          id,
          size,
          name,
          url
        }
      }
    }
}';

$client = new Client($unite_cms_endpoint);

try 
{
    $response = $client->response($query, [], ['Authorization' => $unite_cms_api_token])->all();
    $data = array_shift($response->findPage->result);

    # thumbor is not happy with url schemes, so remove it
    $thumbor_image_url = parse_url($data->image->url, PHP_URL_HOST).parse_url($data->image->url, PHP_URL_PATH);

    ?>
        <h1><?php print $data->headline ?></h1>

        <h2>Thumbor Example</h2>

        <picture>
            <source media="(max-width: 480px)" srcset="https://my_thumbor.com/unsafe/480x0/<?php print $thumbor_image_url ?>">
            <source media="(max-width: 768px)" srcset="https://my_thumbor.com/unsafe/768x0/<?php print $thumbor_image_url ?>">
            <img src="https://my_thumbor.com/unsafe/1200x0/<?php print $thumbor_image_url ?>" alt="My Image" style="width:auto;">
        </picture>

        <hr />

        <h2>cloudimage.io Example</h2>
        
        <picture>
            <source media="(max-width: 480px)" srcset="https://xxxxxxx.cloudimg.io/width/480/x/<?php print $data->image->url ?>">
            <source media="(max-width: 768px)" srcset="https://xxxxxxx.cloudimg.io/width/768/x/<?php print $data->image->url ?>">
            <img src="https://xxxxxx.cloudimg.io/width/1200/x/<?php print $data->image->url ?>" alt="My Image" style="width:auto;">
        </picture>

    <?php

    
} catch (\Exception $e) {
    
    die('Error fetching data: '.$e->getMessage());
}
?>

</body>
</html> 