<html>
<style>
    *{ font-family: DejaVu Sans !important;}
</style>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        *{ font-family: DejaVu Sans !important;}
        .page-break {
            page-break-after: always;
        }
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
    </style>
</head>
<body style="font-family: DejaVu Sans;">
<table>
    <tbody>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>
                        <center>
                        <h2 style="margin: 0; padding: 0; font-size: 28px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.25;">Tűz és munkavédelmi oktatási napló.</h2>
                        </center>
                        <p>Tisztelt {{ $account->lastName }} {{ $account->firstName  }}!</p>
                        <p>Az oktatást elvégezte a tuzesmunkavedelmioktatas.hu rendszere.</p>
                        <p>Az oktatás helye: Az oktatást minden résztvevő online végezte el, így 1 meghatározott helyen nem történt oktatás.</p>
                        <p>Az oktatáson résztvevő munkavállalóknak tetszet kellett kitöltenie, és a tesztet csak úgy tudták helyesen elvégezni, ha megtekintették és értelmezték a videókat. A listában csak olyan munkavállaló szerepelhet, aki megfelelően válaszolt a kérdésekre, így mindenki aki ezen a listán szerepel megválaszolta megfelelően a teszteket.</p>
                        <p>Az oktatás anyaga: A megrendelő cég tűz és munkavédelmi oktatási tematikájából készült videós változat.</p>
                        <p>Az oktatás ideje: Az oktatást a munkáltató a munkaidőn belül kérte a munkatársaktól megtekinteni.</p>
                        <p>Az oktató rendszer neve: tuzesmunkavedelmioktatas.hu</p>
                        <br>
                        <p>A videók tartalma:</p>

                        <ul>
                        <?php if(isset($testNames) && count($testNames)) {
                            foreach($testNames as $name) {
                            ?>
                                <li><?php echo $name; ?></li>
                            <?php
                            }
                        } ?>
                        <p>
                            Én, mint munkavállaló az aláírásommal vállalom, hogy a tuzesmunkavedelmioktatas.hu rendszerében az oktatást elvégeztem,
                            értelmeztem, és ennek megfelelően betartom, ami rám vonatkozik, és amit a munkáltató kér tőlem.
                        </p>

                        <div class="page-break"></div>

                        <p style="margin: 0; padding: 0; font-size: 16px; font-family: 'Avenir Next', 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; line-height: 1.65; font-weight: normal; margin-bottom: 20px;">A tűz és munkavédelmi oktatás elvégezte:</p>

                        <table style="border-collapse: collapse; width: 100%" border="1">
                            <tbody>
                                <tr>
                                    <td align="center">Sorszám</td>
                                    <td align="center">Az oktatás indoka</td>
                                    <td align="center">Név</td>
                                    <td align="center">Beosztás, munkakör</td>
                                    <td align="center">Az oktatás státusza</td>
                                    <td align="center">A munkavállaló aláírás</td>
                                </tr>

                                <?php
                                foreach($tests as $key => $t) { ?>

                                <?php if(($key%12)==0 && $key!=0) { ?>
                                  </tbody>
                                  </table>
                                  <div class="page-break"></div>

                                  <table style="border-collapse: collapse; width: 100%" border="1">
                                    <tbody>
                                        <tr>
                                            <td align="center">Sorszám</td>
                                            <td align="center">Az oktatás indoka</td>
                                            <td align="center">Név</td>
                                            <td align="center">Beosztás, munkakör</td>
                                            <td align="center">Az oktatás státusza</td>
                                            <td align="center">A munkavállaló aláírás</td>
                                        </tr>

                                <?php } ?>

                                <tr>
                                    <td align="center">{{ $key+1 }}</td>
                                    <td align="center">{{ $t->user->educationReason }}</td>
                                    <td align="center">{{ $t->user->lastName }} {{ $t->user->firstName }}</td>
                                    <td align="center">{{ App\Account::getGroupNames($t->user->id) }}</td>
                                    <td align="center">Elvégezte</td>
                                    <td align="center"></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <p>Az oktatás indoka:</p>
                        <ol>
                            <li>Munkába állás</li>
                            <li>Ismétlődő oktatás</li>
                            <li>Tűzvédelmi helyzet megváltozása</li>
                            <li>Új technológia bevezetése</li>
                            <li>Egyéni védőeszközök használata</li>
                            <li>Kockázatértékelésből adódó követelmények</li>
                            <li>Munkakör megváltozása</li>
                        </ol>

                        <div class="page-break"></div>
                            <br>
                            <table style="width:100%" border="0">
                                <tr>
                                    <th align="left">Kelt:</th>
                                    <th align="right">..........................................</th>
                                </tr>
                                <tr>
                                    <td align="left"><strong>{{ date('Y-m-d H:m') }}</strong></td>
                                    <td align="right"><strong>Cég képviselő aláírása</strong></td>
                                </tr>
                            </table>
                            </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
