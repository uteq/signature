<?php

return [
    /*
     * This will be the url Signature will use to handle the actions
     * if the action_route is action the url will for example be https://example.com/action/<signature>
     */
    'action_route' => '/action/{signature}',
    'validate_password_route' => '/validate-action-password/{signature}',

    /*
    * Here you can optionally define the actions, for example: 'action => '\App\SignatureActions\Action'
    * When making a url you will need to provide the key instead of the class path, in the example above it would look like
    * SignatureFacade::make('action', [])->get();
    */
    'actions' => [

    ],
];
