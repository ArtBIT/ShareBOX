define({ "api": [
  {
    "type": "delete",
    "url": "/grupe/:id",
    "title": "Brisanje grupe",
    "name": "DeleteGrupa",
    "description": "<p>Brisanje grupe</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      },
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "delete",
    "url": "/grupe/:id/korisnici/:user_id",
    "title": "Izbacivanje korisnika iz grupe",
    "name": "DeleteKorisnikFromGrupa",
    "description": "<p>Izbacivanje korisnika iz grupe</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      },
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/grupe/:id",
    "title": "Informacije o datoj grupi",
    "name": "GetGrupa",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "grupa",
            "description": "<p>Informacije o grupi</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "grupa.name",
            "description": "<p>Naziv grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao grupu</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.owner_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je vlasnik grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"pregled\"",
              "\"izmena\"",
              "\"kreiranje\""
            ],
            "optional": false,
            "field": "grupa.access_level",
            "description": "<p>Nivo pristupa koji imaju članovi grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "grupa.created",
            "description": "<p>Datum kada je grupa kreirana</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/grupe/:id/korisnici",
    "title": "Spisak korisnika koji pripadaju datoj grupi",
    "name": "GetGrupaKorisnici",
    "description": "<p>Informacije o korisnicima koji pripadaju datoj grupi</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "korisnik",
            "description": "<p>Informacije o korisnicima koji pripadaju grupi</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.role_id",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "korisnik.username",
            "description": "<p>Korisničko ime</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.firstname",
            "description": "<p>Ime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.lastname",
            "description": "<p>Prezime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.activated",
            "description": "<p>Da li je korisnički nalog verifikovan i aktiviran</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.banned",
            "description": "<p>Da li je korisnik prognan</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/grupe",
    "title": "Spisak grupa",
    "name": "GetGrupe",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "grupa",
            "description": "<p>Lista svih grupa</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "grupa.name",
            "description": "<p>Naziv grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao grupu</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.owner_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je vlasnik grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"pregled\"",
              "\"izmena\"",
              "\"kreiranje\""
            ],
            "optional": false,
            "field": "grupa.access_level",
            "description": "<p>Nivo pristupa koji imaju članovi grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "grupa.created",
            "description": "<p>Datum kada je grupa kreirana</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe"
  },
  {
    "type": "post",
    "url": "/grupe/:id",
    "title": "Izmena date grupe",
    "name": "PostGrupa",
    "description": "<p>Izmena grupe</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      },
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Naziv grupe</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "grupa.name",
            "description": "<p>Naziv grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao grupu</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.owner_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je vlasnik grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"pregled\"",
              "\"izmena\"",
              "\"kreiranje\""
            ],
            "optional": false,
            "field": "grupa.access_level",
            "description": "<p>Nivo pristupa koji imaju članovi grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "grupa.created",
            "description": "<p>Datum kada je grupa kreirana</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/grupe",
    "title": "Kreiranje nove grupe",
    "name": "PostGrupa",
    "description": "<p>Kreiranje nove grupe</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Naziv grupe</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "grupa",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "grupa.name",
            "description": "<p>Naziv grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao grupu</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "grupa.owner_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je vlasnik grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "allowedValues": [
              "\"pregled\"",
              "\"izmena\"",
              "\"kreiranje\""
            ],
            "optional": false,
            "field": "grupa.access_level",
            "description": "<p>Nivo pristupa koji imaju članovi grupe</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "grupa.created",
            "description": "<p>Datum kada je grupa kreirana</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    }
  },
  {
    "type": "put",
    "url": "/grupe/:id/korisnici/:user_id",
    "title": "Dodavanje korisnika u grupu",
    "name": "PutKorisnikInGrupa",
    "description": "<p>Dodaj korisnika u grupu</p>",
    "group": "Grupe",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "user_id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Grupe.php",
    "groupTitle": "Grupe",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    }
  },
  {
    "type": "delete",
    "url": "/korisnici/:id",
    "title": "Brisanje datog korisnika",
    "name": "DeleteKorisnik",
    "group": "Korisnici",
    "version": "1.0.0",
    "permission": [
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Korisnici.php",
    "groupTitle": "Korisnici",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/korisnici",
    "title": "Spisak svih korisnika",
    "name": "GetKorisnici",
    "group": "Korisnici",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "korisnik",
            "description": "<p>Lista svih korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.role_id",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "korisnik.username",
            "description": "<p>Korisničko ime</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.firstname",
            "description": "<p>Ime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.lastname",
            "description": "<p>Prezime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.activated",
            "description": "<p>Da li je korisnički nalog verifikovan i aktiviran</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.banned",
            "description": "<p>Da li je korisnik prognan</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Korisnici.php",
    "groupTitle": "Korisnici"
  },
  {
    "type": "get",
    "url": "/korisnici/:id",
    "title": "Informacije o datom korisniku",
    "name": "GetKorisnik",
    "group": "Korisnici",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "korisnik",
            "description": "<p>Informacije o korisniku</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.role_id",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "korisnik.username",
            "description": "<p>Korisničko ime</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.firstname",
            "description": "<p>Ime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.lastname",
            "description": "<p>Prezime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.activated",
            "description": "<p>Da li je korisnički nalog verifikovan i aktiviran</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.banned",
            "description": "<p>Da li je korisnik prognan</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Korisnici.php",
    "groupTitle": "Korisnici",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "put",
    "url": "/korisnici/:id/uloga/:id_uloge",
    "title": "Izmena uloge datog korisnika",
    "name": "PutKorisnikUloga",
    "group": "Korisnici",
    "version": "1.0.0",
    "permission": [
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id_uloge",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "korisnik",
            "description": "<p>Informacije o korisniku</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.role_id",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "korisnik.username",
            "description": "<p>Korisničko ime</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.firstname",
            "description": "<p>Ime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "korisnik.lastname",
            "description": "<p>Prezime korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.activated",
            "description": "<p>Da li je korisnički nalog verifikovan i aktiviran</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "korisnik.banned",
            "description": "<p>Da li je korisnik prognan</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Korisnici.php",
    "groupTitle": "Korisnici",
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ]
      }
    }
  },
  {
    "type": "delete",
    "url": "/korisnici?query=:query",
    "title": "Pretraga korisnika",
    "name": "QueryKorisnik",
    "description": "<p>Pretraga korisnika po imenu, prezimenu i korisničkom imenu</p>",
    "group": "Korisnici",
    "version": "1.0.0",
    "permission": [
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "query",
            "description": "<p>Upit za pretragu</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "autocomplete",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "autocomplete.query",
            "description": "<p>Upit za pretragu</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "autocomplete.suggestions",
            "description": "<p>Rezultati</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "autocomplete.suggestions.value",
            "description": "<p>Združeni string za lakšu pretragu</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "autocomplete.suggestions.data",
            "description": "<p>Informacije o korisniku</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "autocomplete.suggestions.data.id",
            "description": "<p>Jedinstveni identifikator korisnika</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "autocomplete.suggestions.data.username",
            "description": "<p>Korisničko ime</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Korisnici.php",
    "groupTitle": "Korisnici"
  },
  {
    "type": "delete",
    "url": "/merenja/:id",
    "title": "Brisanje merenja",
    "name": "DeleteMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      },
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja koje je obrisano</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    }
  },
  {
    "type": "DELETE",
    "url": "/merenja/:id/redovi/:row_id",
    "title": "Brisanje odabranog reda za dato merenje",
    "name": "DeleteRedoviMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      },
      {
        "name": "administrator",
        "title": "Administrator",
        "description": "<p>Da biste izvršili ovu akciju morate biti administrator</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "row_id",
            "description": "<p>Jedinstveni identifikator reda</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja",
    "title": "Spisak svih merenja",
    "name": "GetMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "merenje",
            "description": "<p>Lista svih merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao merenje</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.group_id",
            "description": "<p>Jedinstveni identifikator grupe kojoj merenje pripada</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.name",
            "description": "<p>Naslov merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.description",
            "description": "<p>Kratak opis merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "merenje.created",
            "description": "<p>Datum kada je merenje kreirano</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.type",
            "description": "<p>Tip merenja created Datum kada je merenje kreirano</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id",
    "title": "Informacije o datom merenju",
    "name": "GetMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "merenje",
            "description": "<p>Informacije o merenju</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao merenje</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.group_id",
            "description": "<p>Jedinstveni identifikator grupe kojoj merenje pripada</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.name",
            "description": "<p>Naslov merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.description",
            "description": "<p>Kratak opis merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "merenje.created",
            "description": "<p>Datum kada je merenje kreirano</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.type",
            "description": "<p>Tip merenja created Datum kada je merenje kreirano</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id/tacke",
    "title": "Prikaz podataka za grafikon",
    "name": "GetMerenjeTacke",
    "description": "<p>Dobavi podatke vezane za merenje potrebne za iscrtavanje grafikona</p>",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "tacke",
            "description": "<p>Podaci merenja za iscrtavanje grafikona</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "tacke.key",
            "description": "<p>Number of miliseconds</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "tacke.vaue",
            "description": "<p>Value at that point</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id/redovi/:row_id",
    "title": "Prikaz jednog reda iz tabele podataka za dato dinamicko merenje",
    "name": "GetRedoviDinamickogMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "row_id",
            "description": "<p>Jedinstveni identifikator reda</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "red",
            "description": "<p>Podaci merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.id",
            "description": "<p>Jedinstveni identifikator reda u tabeli</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.merenje_id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.time",
            "description": "<p>Vreme u sekundama proteklo od pocetka merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.pressure",
            "description": "<p>Pritisak [bar]</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id/redovi/:row_id",
    "title": "Prikaz jednog reda iz tabele podataka za dato staticko merenje",
    "name": "GetRedoviStatickogMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "row_id",
            "description": "<p>Jedinstveni identifikator reda</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "red",
            "description": "<p>Podaci merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.id",
            "description": "<p>Jedinstveni identifikator reda u tabeli</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.merenje_id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "red.datetime",
            "description": "<p>Datum kada je merenje obavljeno</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.ms",
            "description": "<p>Broj milisekundi</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow_relative",
            "description": "<p>Protok [*l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.pressure",
            "description": "<p>Pritisak [bar]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.density",
            "description": "<p>Gustina [kg/m3]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.temperature",
            "description": "<p>Temperatura [°C]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.volume",
            "description": "<p>Zapremina [m³]</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id/redovi",
    "title": "Prikaz svih podataka za dato dinamicko merenje",
    "name": "GetSviRedoviDinamickogMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "red",
            "description": "<p>Podaci merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.id",
            "description": "<p>Jedinstveni identifikator reda u tabeli</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.merenje_id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.time",
            "description": "<p>Vreme u sekundama proteklo od pocetka merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.pressure",
            "description": "<p>Pritisak [bar]</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/merenja/:id/redovi",
    "title": "Prikaz svih podataka za dato staticko merenje",
    "name": "GetSviRedoviStatickogMerenja",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "red",
            "description": "<p>Podaci merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.id",
            "description": "<p>Jedinstveni identifikator reda u tabeli</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.merenje_id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "red.datetime",
            "description": "<p>Datum kada je merenje obavljeno</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.ms",
            "description": "<p>Broj milisekundi</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.flow_relative",
            "description": "<p>Protok [*l/min]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.pressure",
            "description": "<p>Pritisak [bar]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.density",
            "description": "<p>Gustina [kg/m3]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.temperature",
            "description": "<p>Temperatura [°C]</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "red.volume",
            "description": "<p>Zapremina [m³]</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/merenja/",
    "title": "Kreiraj novo merenje",
    "name": "PostMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "group_id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Naziv merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Opis merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "merenje",
            "description": "<p>Informacije o merenju</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao merenje</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.group_id",
            "description": "<p>Jedinstveni identifikator grupe kojoj merenje pripada</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.name",
            "description": "<p>Naslov merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.description",
            "description": "<p>Kratak opis merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "merenje.created",
            "description": "<p>Datum kada je merenje kreirano</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.type",
            "description": "<p>Tip merenja created Datum kada je merenje kreirano</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/merenja/:id/datoteka",
    "title": "Uvoz CSV datoteke u dato merenje",
    "name": "PostMerenjeDatoteka",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "File",
            "optional": false,
            "field": "file",
            "description": "<p>CSV datoteka</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/merenja/:id/redovi",
    "title": "Dodavanje podataka u dato dinamicko merenje",
    "name": "PostRedDinamickoMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "time",
            "description": "<p>Vreme u sekundama proteklo od pocetka merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "pressure",
            "description": "<p>Pritisak [bar]</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "Jedinstveni",
            "description": "<p>identifikator reda</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/merenja/:id/redovi",
    "title": "Dodavanje podataka u dato staticko merenje",
    "name": "PostRedStatickoMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "DateTime",
            "optional": false,
            "field": "datetime",
            "description": "<p>Datum i vreme kada je izvrešeno očitavanje formata &quot;Y-m-d H:i:s&quot;</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms",
            "description": "<p>Broj milisekundi od gore navedenog datuma i vremena</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "flow",
            "description": "<p>Protok [l/min]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "flow_relative",
            "description": "<p>Protok [*l/min]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "pressure",
            "description": "<p>Pritisak [bar]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "density",
            "description": "<p>Gustina [kg/m3]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "temperature",
            "description": "<p>Temperatura [°C]</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "volume",
            "description": "<p>Zapremina [m³]</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "Jedinstveni",
            "description": "<p>identifikator reda</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "post",
    "url": "/merenja/:id",
    "title": "Ažuriraj postojeće merenje",
    "name": "PutMerenje",
    "group": "Merenja",
    "version": "1.0.0",
    "permission": [
      {
        "name": "vlasnik",
        "title": "Vlasnik grupe",
        "description": "<p>Da biste izvršili ovu akciju morate biti vlasnik grupe</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>Jedinstveni identifikator Merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "group_id",
            "description": "<p>Jedinstveni identifikator grupe</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Naziv merenja</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>Opis merenja</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "merenje",
            "description": "<p>Informacije o merenju</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.id",
            "description": "<p>Jedinstveni identifikator merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.user_id",
            "description": "<p>Jedinstveni identifikator korisnika koji je kreirao merenje</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "merenje.group_id",
            "description": "<p>Jedinstveni identifikator grupe kojoj merenje pripada</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.name",
            "description": "<p>Naslov merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.description",
            "description": "<p>Kratak opis merenja</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "merenje.created",
            "description": "<p>Datum kada je merenje kreirano</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "merenje.type",
            "description": "<p>Tip merenja created Datum kada je merenje kreirano</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Merenja.php",
    "groupTitle": "Merenja",
    "error": {
      "fields": {
        "Error 400": [
          {
            "group": "Error 400",
            "optional": false,
            "field": "NeispravanZahtev",
            "description": "<p>Zahtev koji ste poslali ne sadrži sve potrebne parametre</p>"
          }
        ],
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ],
        "Error 401": [
          {
            "group": "Error 401",
            "optional": false,
            "field": "Neautorizovano",
            "description": "<p>Nemate dozvolu za pristup traženom resursu</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/uloge/:id",
    "title": "Naziv uloge",
    "name": "GetNazivUloge",
    "group": "Uloge",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "id",
            "description": "<p>jedinstveni identifikator uloge</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "Naziv",
            "description": "<p>uloge</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Roles.php",
    "groupTitle": "Uloge",
    "error": {
      "fields": {
        "Error 404": [
          {
            "group": "Error 404",
            "optional": false,
            "field": "NotFound",
            "description": "<p>Traženi resurs nije pronadjen</p>"
          }
        ]
      }
    }
  },
  {
    "type": "get",
    "url": "/uloge",
    "title": "Spisak svih uloga",
    "name": "GetUloge",
    "group": "Uloge",
    "version": "1.0.0",
    "permission": [
      {
        "name": "korisnik",
        "title": "Korisnik",
        "description": "<p>Da biste izvršili ovu akciju morate biti registrovani korisnik</p>"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "uloga",
            "description": "<p>Lista svih Uloga</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "uloga.key",
            "description": "<p>Jedinstveni identifikator uloge</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "uloga.value",
            "description": "<p>Naziv uloge</p>"
          }
        ]
      }
    },
    "filename": "public/application/controllers/api/v1/Roles.php",
    "groupTitle": "Uloge"
  }
] });
