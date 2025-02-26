let Keyboard = window.SimpleKeyboard.default;

let commonKeyboardOptions = {
  onChange: input => onChange(input),
  onKeyPress: button => onKeyPress(button),
  theme: "simple-keyboard hg-theme-default hg-layout-default",
  physicalKeyboardHighlight: true,
  syncInstanceInputs: true,
  mergeDisplay: true,
 // debug: true
};

let keyboard = new Keyboard(".simple-keyboard-main", {
  ...commonKeyboardOptions,
  /**
   * Layout by:
   * Sterling Butters (https://github.com/SterlingButters)
   */
  layout: {
    default: [
      "` 1 2 3 4 5 6 7 8 9 0 - = {backspace}",
      "{tab} q w e r t y u i o p [ ] \\",
      "{capslock} a s d f g h j k l ; ' {enter}",
      "{shiftleft} z x c v b n m , . / {shiftright}",
      "{controlleft} {space} {controlright}"
    ],
    shift: [
      "~ ! @ # $ % ^ & * ( ) _ + {backspace}",
      "{tab} Q W E R T Y U I O P { } |",
      '{capslock} A S D F G H J K L : " {enter}',
      "{shiftleft} Z X C V B N M < > ? {shiftright}",
      "{controlleft} {space} {controlright}"
    ]
  },
  display: {
    "{escape}": "esc ⎋",
    "{tab}": "tab ⇥",
    "{backspace}": "backspace ⌫",
    "{enter}": "enter ↵",
    "{capslock}": "caps lock ⇪",
    "{shiftleft}": "shift ⇧",
    "{shiftright}": "shift ⇧",
    "{controlleft}": "ctrl ⌃",
    "{controlright}": "ctrl ⌃",
    "{altleft}": "alt ⌥",
    "{altright}": "alt ⌥",
    "{metaleft}": "cmd ⌘",
    "{metaright}": "cmd ⌘"
  }
});

/*let keyboardControlPad = new Keyboard(".simple-keyboard-control", {
  ...commonKeyboardOptions,
  layout: {
    default: [
      "{prtscr} {scrolllock} {pause}",
      "{insert} {home} {pageup}",
      "{delete} {end} {pagedown}"
    ]
  }
});

let keyboardArrows = new Keyboard(".simple-keyboard-arrows", {
  ...commonKeyboardOptions,
  layout: {
    default: ["{arrowup}", "{arrowleft} {arrowdown} {arrowright}"]
  }
});*/

//let keyboardNumPad = new Keyboard(".simple-keyboard-numpad", {
//  ...commonKeyboardOptions,
//  layout: {
//    default: [
//      "{numpaddivide} {numpadmultiply}",
//      "{numpad7} {numpad8} {numpad9}",
//      "{numpad4} {numpad5} {numpad6}",
//      "{numpad1} {numpad2} {numpad3}",
//      "{numpad0} {numpaddecimal}"
//    ]
//  }
//});
//
//let keyboardNumPadEnd = new Keyboard(".simple-keyboard-numpadEnd", {
//  ...commonKeyboardOptions,
//  layout: {
//    default: ["{numpadsubtract}", "{numpadadd}", "{numpadenter}"]
//  }
//});

/**
 * Physical Keyboard support
 * Whenever the input is changed with the keyboard, updating SimpleKeyboard's internal input
 */
/*document.addEventListener("keydown", event => {
  // Disabling keyboard input, as some keys (like F5) make the browser lose focus.
  // If you're like to re-enable it, comment the next line and uncomment the following ones
  event.preventDefault();
});*/

document.querySelector(".input").addEventListener("input", event => {
  let input = document.querySelector(".input").value;
  keyboard.setInput(input);
});


function onChange(input) {
  document.querySelector(".input").value = input;
  keyboard.setInput(input);

 // console.log("Input changed", input);
}

function onKeyPress(button) {
  console.log("Button pressed", button);
    setTimeout(()=>{
        $("#produsesearch-nume").trigger('input');
    },
            
            100);
  


  /**
   * If you want to handle the shift and caps lock buttons
   */
  if (
    button === "{shift}" ||
    button === "{shiftleft}" ||
    button === "{shiftright}" ||
    button === "{capslock}"
  )
    handleShift();
}

function handleShift() {
  let currentLayout = keyboard.options.layoutName;
  let shiftToggle = currentLayout === "default" ? "shift" : "default";

  keyboard.setOptions({
    layoutName: shiftToggle
  });
}

document.addEventListener("keydown", event => {});


