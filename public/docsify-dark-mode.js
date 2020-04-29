const plugin = (hook, vm) => {
  const trans = () => {
    document.documentElement.classList.add('transition');
    window.setTimeout(() => {
      document.documentElement.classList.remove('transition');
    }, 800);
  };

  const setColor = ({ background, toggleBtnBg, textColor }) => {
    document.documentElement.style.setProperty(
      '--docsify_dark_mode_bg',
      background
    );
    document.documentElement.style.setProperty(
      '--docsify_dark_mode_btn',
      toggleBtnBg
    );
    document.documentElement.style.setProperty('--text_color', textColor);
  };

  let theme = { dark: {}, light: {} };
  const defaultConfig = {
    dark: {
      background: '#1c2022',
      toggleBtnBg: '#4d29b0',
      textColor: '#b4b4b4',
    },
    light: {
      background: 'white',
      toggleBtnBg: '#3f9ef7',
      textColor: 'var(--theme-color)',
    },
  };

  theme = { ...defaultConfig, ...vm.config.darkMode };

  hook.afterEach(function (html, next) {
    const darkElement = ` <div id="dark_mode">
             <input class="container_toggle" type="checkbox" id="switch" name="mode" />
             <label for="switch">Toggle</label>
           </div>`;
    html = `${darkElement}${html}`;
    next(html);
  });

  hook.doneEach(function () {
    let currColor;
    if (localStorage.getItem('DOCSIFY_DARK_MODE')) {
      currColor = localStorage.getItem('DOCSIFY_DARK_MODE');
      setColor(theme[`${currColor}`]);
    } else {
      currColor = 'light';
      setColor(theme.light);
    }

    const checkbox = document.querySelector('input[name=mode]');

    checkbox.addEventListener('change', function () {
      // Dark
      if (currColor === 'light') {
        trans();
        setColor(theme.dark);
        localStorage.setItem('DOCSIFY_DARK_MODE', 'dark');
        currColor = 'dark';
      } else {
        trans();
        setColor(theme.light);
        localStorage.setItem('DOCSIFY_DARK_MODE', 'light');
        currColor = 'light';
      }
    });
  });
};

window.$docsify.plugins = [].concat(plugin, window.$docsify.plugins);
