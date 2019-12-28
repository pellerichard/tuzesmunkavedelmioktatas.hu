# Tűz és munkavédelmi oktatás

A weboldal a PHP 5.6-os verzióját használja, illetve Laravel 5.4-et, mint API kiszolgálót, és VueJS-t, mint frontend keretrendszert. A weboldal "SPA" (Single Page Application), amely a mai mobil applikáció standardekre épül, ámbár nincs neki natív telefonos applikációja. A weboldal fejlesztése során a teljes reszponzívitásra törekedtem, hogy mind mobil - tablet - és számítógépen is használható legyen az applikáció. Kiemelném hogy nem minden sor részlet a mai kornak megfelelő módszerrel lett implementálva, mivel több ezres felhalhasználóbázis használta nap mint nap, ezért voltak S.O.S megoldások, így nem minden sor kódért vállalom a felelősséget.

## Fontos megjegyzés
A weboldal egy egészséges és kölcsönös barátság kialakulása közben született, ahol kölcsönös bizalommal és tisztelettel fejlesztettem, ámbár ez a kapcsolat megszakadt, és megromlott, az illető akinek a weboldalt fejlesztettem (nevet nem említek) inkorrekt lett a vége felé, és nem teljesítette a kettőnk megállapodásában meghatározott követelményeket. A kódbázis alatt semmilyen license nincs, sem szerződés, semmilyen legális formában nem létezik, eszmei értéke volt egészen a publikálásának napjáig.

# Demo verzió
A demo verzió ismeretlen ideig lesz elérhető, ha jóval a publikáció után tévedtél ide, viszont a demo link nem elérhető, ez esetben már nem is lesz. Telepítési útmutatót lentebb találhatsz, ahol a saját fejlesztői környezetedben működésre birhatod a weboldalt, és a dependenciáit. A demo verzió igen sok hibát tartalmaz, mivel olyan esetek előfordulnak teszt során, ami élesben például soha nem fordulhatott elő, lásd: összes teszt törlése, nincs egy darab cég se, ecetera, így előfordulhat olyan eset hogy nincs lekezelve kód szinten else ág adott lekérdezésnél.

# Dependenciák
- PHP 5.6
- Node v10.16.0 (vagy frissebb)
- Node Package Manager 6.9.0 (vagy frissebb)
- Composer v1.9.0

# Telepítési útmutató
- npm install
- npm run watch
- composer install
- php artisan key:generate
- php artisan serve

# Levelező szerver beállítása
.env fájlba adjuk meg a levelező szerverünk beállításait.
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=Felhasznalonev
MAIL_PASSWORD=Jelszo
MAIL_ENCRYPTION=tls
```

> A weboldal demo verziója elérhető az alábbi linken: [Kattints ide](https://tuzesmunkavedelmioktatas.demo.pellerichard.hu)
