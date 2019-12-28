<?php
/**
 *
 * @var $successRate string
 * @var $testName string
 * @var $account array()
 */
?>

<table class="body-wrap" style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; height: 100%; background: #efefef; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important;">
    <tbody>
    <tr style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65;">
        <td class="container" style="margin: 0 auto !important; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; display: block !important; clear: both !important; max-width: 580px !important;">
            <!-- Message start -->
            <table style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; border-collapse: collapse; width: 100% !important;">
                <tbody>
                <tr style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65;">
                    <td class="masthead" style="margin: 0; padding: 80px 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; background: #3498db; color: white;" align="center">
                        <h1 style="margin: 0 auto !important; padding: 0; font-size: 32px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.25; margin-bottom: 20px; max-width: 90%; text-transform: uppercase;">Tűz és munkavédelmi oktatás</h1>
                    </td>
                </tr>
                <tr style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65;">
                    <td class="content" style="margin: 0; padding: 30px 35px; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; background: white;">
                        <h2 style="margin: 0; padding: 0; font-size: 28px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.25; margin-bottom: 20px;">Tisztelt {{ $account->lastName }} {{ $account->firstName  }}!</h2>

                        <p>Sikeresen mindenki kitöltötte a tesztet.</p>
                        <p>Kérlek nyomtasd ki, majd tedd el ezt a dokumentumot, mert ez egy hivatalosan generált tűz és munkavédelmi oktatási napló.</p>
                        <p><strong>A dokumentumot mellékletként csatoltuk az e-mailhez.</strong></p>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- START FOOTER -->
            <div class="footer">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td class="content-block">
                            <span class="apple-link">Tűz és Munkavédelmi Oktatás</span>
                            <br>Nem szeretnél több ilyen levelet kapni? <a href="https://tuzesmunkavedelmioktatas.hu/korlevel-tiltas/{{ $blacklistEmail }}">Kattints ide</a>
                            <p>Hibát észleltél? Itt tudod jelenteni: <a href="mailto:oktatas@emailcimed.hu">oktatas@emailcimed.hu</a></p>
                        </td>
                    </tr>
                </table>
            </div>

        </td>
    </tr>
    </tbody>
</table>

<style>
    .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
    }
    .footer {
        clear: both;
        Margin-top: 10px;
        text-align: center;
        width: 100%; }
    .footer td,
    .footer p,
    .footer span,
    .footer a {
        color: #999999;
        font-size: 12px;
        text-align: center; }
    @media all {
        .ExternalClass {
            width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%; }
        .apple-link a {
            color: inherit !important;
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            text-decoration: none !important; }
        .btn-primary table td:hover {
            background-color: #34495e !important; }
        .btn-primary a:hover {
            background-color: #34495e !important;
            border-color: #34495e !important; } }
</style>