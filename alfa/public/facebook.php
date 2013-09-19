<html>
<head>
    <link type="text/css" rel="stylesheet" href="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yU/r/IBb8dQrlYJ3.css" />
    <link type="text/css" rel="stylesheet" href="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yK/r/bqfcuHT6v0u.css" />
    <link type="text/css" rel="stylesheet" href="https://fbstatic-a.akamaihd.net/rsrc.php/v2/yJ/r/k4jblsFJzCO.css" />
</head>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '627093887323635', // App ID
            channelUrl : '//alfa.checkinthings.com/facebook.php', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });

        // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
        // for any authentication related change, such as login, logout or session refresh. This means that
        // whenever someone who was previously logged out tries to log in again, the correct case below
        // will be handled.
        FB.Event.subscribe('auth.authResponseChange', function(response) {
            // Here we specify what we do with the response anytime this event occurs.
            if (response.status === 'connected') {
                // The response object is returned with a status field that lets the app know the current
                // login status of the person. In this case, we're handling the situation where they
                // have logged in to the app.
                testAPI();
            } else if (response.status === 'not_authorized') {
                // In this case, the person is logged into Facebook, but not into the app, so we call
                // FB.login() to prompt them to do so.
                // In real-life usage, you wouldn't want to immediately prompt someone to login
                // like this, for two reasons:
                // (1) JavaScript created popup windows are blocked by most browsers unless they
                // result from direct interaction from people using the app (such as a mouse click)
                // (2) it is a bad experience to be continually prompted to login upon page load.
                FB.login();
            } else {
                // In this case, the person is not logged into Facebook, so we call the login()
                // function to prompt them to do so. Note that at this stage there is no indication
                // of whether they are logged into the app. If they aren't then they'll see the Login
                // dialog right after they log in to Facebook.
                // The same caveats as above apply to the FB.login() call here.
                FB.login();
            }
        });
    };

    // Load the SDK asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));

    // Here we run a very simple test of the Graph API after login is successful.
    // This testAPI() function is only called in those cases.
    function testAPI() {
        console.log('Welcome!  Fetching your information.... ');
        FB.api('/me', function(response) {
            console.log('Good to see you, ' + response.name + '.');
        });

        showLogoutButton();
    }

    function isset () {
        // http://kevin.vanzonneveld.net
        // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +   improved by: FremyCompany
        // +   improved by: Onno Marsman
        // +   improved by: Rafał Kukawski
        // *     example 1: isset( undefined, true);
        // *     returns 1: false
        // *     example 2: isset( 'Kevin van Zonneveld' );
        // *     returns 2: true
        var a = arguments,
            l = a.length,
            i = 0,
            undef;

        if (l === 0) {
            throw new Error('Empty isset');
        }

        while (i !== l) {
            if (a[i] === undef || a[i] === null) {
                return false;
            }
            i++;
        }
        return true;
    }

    function fbLogout() {
        var uid;
        var accessToken;

        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token
                // and signed request each expire
                uid = response.authResponse.userID;
                accessToken = response.authResponse.accessToken;

                console.log('uid: '+uid);
                console.log('accessToken: '+accessToken);
            } else if (response.status === 'not_authorized') {
                // the user is logged in to Facebook,
                // but has not authenticated your app
            } else {
                // the user isn't logged in to Facebook.
            }
        });

        if (isset(uid)) {
            FB.logout(function(response) {
                // user is now logged out
            });
            hideLogoutButton();
            funcx();
        }
    }

    function showLogoutButton() {
        document.getElementById('fb_logout').innerHTML = '<a href="#logout" onclick="fbLogout();"><img border="0" src="/img/fb_logout.png"></a>';
    }

    function hideLogoutButton() {
        document.getElementById('fb_logout').innerHTML = '';
        document.getElementById('fb_login').innerHTML = 'You have successfully logged out from FB.';
    }

    function funcx()
    {
        // your code here
        // break out here if needed
        setTimeout(funcx, 1000);
    }

</script>

<!--
  Below we include the Login Button social plugin. This button uses the JavaScript SDK to
  present a graphical Login button that triggers the FB.login() function when clicked.

  Learn more about options for the login button plugin:
  /docs/reference/plugins/login/ -->
<div id="fb_login"><fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button></div>

<div id="fb_logout"></div>

<script src="/js/jquery-1.10.0.min.js"></script>

</body>
</html>
