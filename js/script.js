const breakpoint = window.matchMedia("only screen and (max-width: 1200px) and (orientation: portrait)");

// öffne Burger-Menü für Mobilansicht
// kein breakpoint nötig, da über CSS der Burger in Desktopansicht ausgeblendet ist
function toggleBurgerMenu(nav, navItems, state) {
    if (state === false) {
        for(let navItem of navItems) {
            navItem.style.display = 'flex';
            navItem.style.gridColumn = '1 / span 4';  
            navItem.style.justifySelf = 'center';
            
            nav.style.backgroundColor = 'var(--farbe_dunkel)';
            if (navItem === navItems[navItems.length-1]) {
                navItem.style.display = 'flex';
            }            
            
        }
        return true;
    } else {
        for(let navItem of navItems) {
            navItem.style.display = 'none';
            nav.style.backgroundColor = 'initial';
        }        
        return false;
    }
}


// verlinke auf Unterseiten, für Desktopansicht Zeit lassen für die Animation
function redirect(whereTo, breakpoint) {
    if(breakpoint.matches) {
       window.location=whereTo; 
    } else {      
        window.setTimeout(() => {window.location=whereTo}, 1000);
    }    
}  


// zoome in Shopübersicht auf das Gesicht der Markthändler
function zoomIn(mainMerchant, breakpoint) {
    if(breakpoint.matches) {
       return; 
    }    
    mainMerchant.style.transition = 'transform 1s';
    mainMerchant.style.transform = 'scale(2)';
}


// lasse die nicht angewählten Händler in den Hintergrund treten
function fadeOut(secondaryMerchant, tertiaryMerchant, breakpoint) {
    if(breakpoint.matches) {
       return; 
    }    
    secondaryMerchant.style.opacity = '50%';
    secondaryMerchant.style.transform = 'scale(2)';                 
    secondaryMerchant.style.transition = 'transform 1s, opacity 1s, filter 1s';
    secondaryMerchant.style.filter = 'saturate(0)';
    tertiaryMerchant.style.opacity = '50%';
    tertiaryMerchant.style.transform = 'scale(2)';                
    tertiaryMerchant.style.transition = 'transform 1s, opacity 1s, filter 1s';
    tertiaryMerchant.style.filter = 'saturate(0)';
}

// deaktiviere Animation für mobil
// kürze Figures und die Section in der alle Händlerbilder sind
// verlängere gleichzeitig die Section mit den kaufen/verkaufen Items
// -> animierter fließender Übergang von Übersichtsseite zu Einzelshops
function animateRemainder(merchantSection, merchantFigures, inventorySections, toggleButtons, breakpoint) {
    if(breakpoint.matches) {
       return; 
    }
    merchantSection.style.transition = 'height 1s';
    merchantSection.style.height = '35vh';
    for (let figure of merchantFigures) {
        figure.style.height = '35vh';
        figure.style.width = '20vw';
        figure.style.transition = 'height 1s, width 1s';
    }
    for (let section of inventorySections) {
        section.style.display = 'grid';
    }
    for (let buttons of toggleButtons) {
        buttons.style.display = 'flex';
    }
    
}

// füge Itemkarten Klasse hinzu, damit die Eigenschaften der aufgedeckten Karte
// über CSS gesteuert werden können
// -> Aufdecken der Karte
function setItemcardEigenschaften(itemcard) {
    let eigenschaften = itemcard.querySelectorAll(':is(.itemBeschreibung, .buff, table, form)');
    itemcard.classList.add('itemkarte_aufgedeckt');
    for(let eigenschaft of eigenschaften) {
        eigenschaft.style.display = 'flex';
    }   
    return true;
}

// füge Itemkarten im Inventar Klasse hinzu, damit die von den anderen Itemkarten
// abweichenden Eigenschaften der aufgedeckten Inventarkarte über CSS gesteuert
// werden können
// -> Aufdecken der Karte
function setInventorycardEigenschaften(itemcard, breakpoint) {
    let eigenschaften = itemcard.querySelectorAll(':is(.itemBeschreibung, .buff, table, form)');
    itemcard.classList.add('itemkarte_aufgedeckt');
    itemcard.classList.add('inventarkarte_aufgedeckt');
    for(let eigenschaft of eigenschaften) {
        eigenschaft.style.display = 'flex';
    }    
}

// entferne die Klassen von Itemkarten wieder
// -> Zudecken der Karte
function resetItemcardEigenschaften(itemcard) {
    let eigenschaften = itemcard.querySelectorAll(':is(.itemBeschreibung, .buff, table, form)');
    for(let eigenschaft of eigenschaften) {
        eigenschaft.style.display = 'none';
    }    
    itemcard.classList.remove('itemkarte_aufgedeckt');
    itemcard.classList.remove('inventarkarte_aufgedeckt');
    return false;
}

// kein Toggle-Mechanismus in Desktopansicht, da Inventare Händler/Player nebeneinander
// bei mobil toggle von kaufen/verkaufen Container
function toggleKaufen(container, oldContainer, toggleButton, oldToggleButton, breakpoint) {
    if(breakpoint.matches) {
        container.style.display = 'grid';
        container.style.backgroundImage = 'linear-gradient(var(--farbe_mittel), var(--farbe_dunkel)';
        oldContainer.style.display = 'none';
        toggleButton.style.backgroundColor = 'var(--farbe_mittel)';
        oldToggleButton.style.backgroundColor = 'var(--farbe_dunkel)';
    } else {
        return;
    }
}

// toggle zwischen Login/Registrieren-Funktion im gleichen Container
// deaktiviere Animation für mobil
function toggleLogin(state, container, oldContainer, toggleButton, oldToggleButton, breakpoint) {
    if(state === 'login') {
        container.style.marginRight = '0';
        oldContainer.style.marginLeft = '30rem';
        // ohne Animation für mobil        
        if(!breakpoint.matches) {
            container.style.transition = 'margin-right 0.6s';
            oldContainer.style.transition = 'margin-left 0.6s';
        }
    } else if(state === 'registrieren') {
        container.style.marginLeft = '0';
        oldContainer.style.marginRight = '30rem';
        // ohne Animation für mobil            
        if(!breakpoint.matches) {
            container.style.transition = 'margin-left 0.6s';
            oldContainer.style.transition = 'margin-right 0.6s';
        }
    }
    toggleButton.style.backgroundColor = 'var(--farbe_mittel)';
    oldToggleButton.style.backgroundColor = 'var(--farbe_dunkel)';        
}

// klappe Passwort ändern-Feld in Account Settings auf
function aufklappen(e) {
    let form = e.target.nextElementSibling;
    form.style.display = 'flex';
    form.style.flexWrap = 'wrap';
    form.style.justifyContent = 'center';
    for (let child of form.children) {
        child.style.width = '60%';
    }
    form.lastElementChild.style.backgroundColor = 'var(--farbe_hell)';
    e.target.removeEventListener('click', aufklappen);
    e.target.addEventListener('click', zuklappen);
}

// klappe Passwort ändern-Feld in Account Settings zu
function zuklappen(e) {
    let form = e.target.nextElementSibling;                
    form.style.display = 'none';
    e.target.removeEventListener('click', zuklappen);
    e.target.addEventListener('click', aufklappen);
}

// füge Event Listener für Burger Menü hinzu
function addBurgerMenu() {
    const burgerMenu = document.querySelector('.burgerMenu');
    const burgerMenuLink = document.querySelectorAll('.burgerMenuLink');
    const navMenu = document.querySelector('nav');
    let isMenuOpen = false;
    burgerMenu.addEventListener('click', () => {isMenuOpen = toggleBurgerMenu(navMenu, burgerMenuLink, isMenuOpen);});    
}