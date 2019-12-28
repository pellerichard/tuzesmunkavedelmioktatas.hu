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
                        <p>Fontos, hogy tudja, hogy az alkalmazottak az oktatást megtekintették, illetve helyesen válaszoltak-e a megadott kérdésekre.&nbsp;<br>Ez az oktatás az önnél dolgozók egészséget óvja meg, és a cége értékeit. Fontos , hogy az emberek végignézzék az online oktatást és válaszoljanak a kérdésekre, hisz ekkor tudjuk meg, hogy hatékony volt-e az oktatás.&nbsp;</p>
                        <p style="margin: 0; padding: 0; font-size: 16px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; font-weight: normal; margin-bottom: 20px;">A tűz és munkavédelmi oktatás elvégezte:</p>
                        <table style="border-collapse: collapse; width: 100%" border="1">
                            <tbody>
                            <tr style="height: 23px;">
                                <td style="width: 50%; height: 23px;">Név</td>
                                <td style="width: 25%; height: 23px;">Státusz</td>
                                <td style="width: 25%;">E-mail</td>
                            </tr>

                            <?php
                                foreach($tests as $t) {
                                    if($t['successRate']>=85 && !isset($t['notFilledCompletely'])) { ?>
                                        <tr style="height: 23px;">
                                            <td style="width: 50%; height: 23px;">{{ $t->user->lastName }} {{ $t->user->firstName }}</td>
                                            <td style="width: 25%; height: 23px;"><span style="background-color: #00ff00;">Elvégezte</span></td>
                                            <td style="width: 25%;"><span style="background-color: #00ff00;">{{ $t->user->email }}</span></td>
                                        </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <p>A tűz és munkavédelmi oktatást még nem végezte el</p>
                        <table style="border-collapse: collapse; width: 100%" border="1">
                            <tbody>
                            <tr style="height: 23px;">
                                <td style="width: 50%; height: 23px;">Név</td>
                                <td style="width: 25%; height: 23px;">Státusz</td>
                                <td style="width: 25%;">E-mail</td>
                            </tr>
                            <?php
                            foreach($tests as $t) {
                                if($t['successRate']<85 || isset($t['notFilledCompletely'])) { ?>
                                    <tr style="height: 23px;">
                                        <td style="width: 50%; height: 23px;">{{ $t->user->lastName }} {{ $t->user->firstName }}</td>
                                        <td style="width: 25%; height: 23px;"><span style="background-color: #ff0000;">
                                        <?php
                                        if(isset($t['notFilledCompletely']) && !isset($t['notFilledAtAll']) && $t['successRate']!=-1) {
                                            echo 'Részlegesen töltötte ki a teszteket.';
                                        } else {
                                            echo ((isset($t['notFilledAtAll']) && $t['notFilledAtAll']==true) ? 'Nem végezte el.' : 'Nem teljesített 85% felett a kérdésekre.');
                                        }
                                        ?>
                                        </span></td>
                                        <td style="width: 25%;"><span>{{ $t->user->email }}</span></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                        <br>
                        <table style="margin: 0px; padding: 0px; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif; line-height: 1.65; border-collapse: collapse; width: 100%;">
                            <tbody>
                            <tr style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65;">
                                <td style="margin: 0px; padding: 0px; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', Helvetica, Helvetica, Arial, sans-serif; line-height: 1.65; width: 100%;" align="center">
                                    <p style="margin: 0; padding: 0; font-size: 16px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; font-weight: normal; margin-bottom: 20px;"><a class="button" style="margin: 0; padding: 0; font-size: 100%; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; color: white; text-decoration: none; display: inline-block; background: #3498db; border: solid #3498db; border-width: 10px 20px 8px; font-weight: bold; border-radius: 4px;" href="https://tuzesmunkavedelmioktatas.hu/alkalmazott-ertesites/{{ $token }}" target="_blank">Értesítés küldése, hogy a feladatot végezzék el</a></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <p style="margin: 0; padding: 0; font-size: 16px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; font-weight: normal; margin-bottom: 20px;">Ez a gomb teszi lehetővé, hogy az összes nem végzett felhasználónak küldjön egy e-mail értesítőt, hogy az oktatást pótolják!</p>
                        <p style="margin: 0; padding: 0; font-size: 16px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; font-weight: normal; margin-bottom: 20px;">A következő levelet 48 óra múlva kapja, hogy időben értesüljön, hogy a feladat el lett-e végezve!</p>
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