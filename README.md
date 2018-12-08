# Prekybos platforma

**Prisijungimai prie sistemos:**

                    ADMIN:                  USER:
    email:          admin@admin.lt          user@user.lt
    pass:           admin                   user

**Rolės:**

    Sandėlio valdytojui:    "ROLE_VALDYTOJAS"
    Sandėlio darbininkui:   "ROLE_DARBININKAS"
    Vartotojui:             "ROLE_USER"
    Administratoriui:       "ROLE_ADMIN"
    Moderatoriui:           "ROLE_MOD"
    
    Pavyzdys, kai vartotojas turi kelias roles (pvz kai vartotojas yra sandėlio valdytojas):
    ["ROLE_VALDYTOJAS", "ROLE_USER"]

**Rolių pavyzdžiai:**

    Vartotojui:         ["ROLE_USER"]
    Darbininkui:        ["ROLE_USER", "ROLE_DARBININKAS"]
    Valdytojui:         ["ROLE_USER", "ROLE_VALDYTOJAS"]
    Moderatoriui:       ["ROLE_USER", "ROLE_MOD"]
    Administratoriui:   ["ROLE_USER", "ROLE_MOD", "ROLE_ADMIN"]

**Įrašų šalinimas**

Bet kokių įrašų šalinimas realizjuoamas naudojant "soft delete", per įrašą *arPasalinta*.

Kaip pavyzdį galima paimti parduotuvės prekės "Entity".

Norint iš duombazės išsitraukti masyvą su nepašalintais įrašais, naudojame ne funkciją *findAll*, bet funkciją *->findBy(['arPasalinta' => false])*

**Templeitas**

Template'ui naudojamas "Material Design for Bootstrap":
https://fezvrasta.github.io/bootstrap-material-design/
