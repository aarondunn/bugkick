<html>
    <body>
    <div style='border: 1px solid silver; margin: 10px; padding: 10px;'>
        <h3><strong><?php echo $user->getUserName() ?> has invited you to join Bugkick.com.</strong></h3>

        <p>Bugkick is a simple online task management app, helping people organize work and collaborate online.
        <span style="line-height: 1.6em;">Because </span><span style="line-height: 1.6em;"><?php echo $user->getUserName() ?>
        referred you, we are offering <strong>a year free</strong> of our pro membership which is <strong>normally $108</strong>. </span></p>

        <p>To get started, please <?php echo CHtml::link('Sign Up', $registerUrl); ?>. <span style="line-height: 1.6em;">
        To redeem your one year free, please enter the code: </span><strong><strong style="line-height: 1.6em;">refer_a_friend </strong></strong></p>

        <p>Our payment system (powered by Stripe.com) will ask for a credit card, but with that code you will not be charged. If you are happy with the site,
        please consider staying with us as a paying member after your first year, or you will be automatically downgraded to our free plan.</p>

        <p>Enjoy. Please let us know if you need help or have suggestions.</p>

        <p>Best,<br />
        Bugkick.com</p>

    </div>
    </body>
</html>