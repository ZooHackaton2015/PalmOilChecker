using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Runtime.InteropServices.WindowsRuntime;
using Windows.ApplicationModel;
using Windows.ApplicationModel.Activation;
using Windows.Foundation;
using Windows.Foundation.Collections;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;
using Windows.UI.Xaml.Controls.Primitives;
using Windows.UI.Xaml.Data;
using Windows.UI.Xaml.Input;
using Windows.UI.Xaml.Media;
using Windows.UI.Xaml.Media.Animation;
using Windows.UI.Xaml.Navigation;

// Dokumentaci k šabloně prázdné aplikace najdete na adrese http://go.microsoft.com/fwlink/?LinkId=391641.

namespace WindowsPhone
{
    /// <summary>
    /// Poskytuje chování specifické pro aplikaci, doplňující výchozí třídu Application.
    /// </summary>
    public sealed partial class App : Application
    {
        private TransitionCollection transitions;

        /// <summary>
        /// Inicializuje objekt aplikace typu singleton. Jedná se o první řádek spuštěného vytvořeného kódu,
        /// který je proto logickým ekvivalentem metod main() a WinMain().
        /// </summary>
        public App()
        {
            this.InitializeComponent();
            this.Suspending += this.OnSuspending;
        }

        /// <summary>
        /// Vyvoláno při normálním spuštění aplikace koncovým uživatelem. Ostatní vstupní body
        /// budou použity při spuštění aplikace pro otevření určitého souboru, ke zobrazení
        /// výsledků hledání a podobně.
        /// </summary>
        /// <param name="e">Podrobnosti o požadavku spuštění a procesu.</param>
        protected override void OnLaunched(LaunchActivatedEventArgs e)
        {
#if DEBUG
            if (System.Diagnostics.Debugger.IsAttached)
            {
                this.DebugSettings.EnableFrameRateCounter = true;
            }
#endif

            Frame rootFrame = Window.Current.Content as Frame;

            // Neopakovat inicializaci aplikace, pokud má objekt Window již obsah,
            // pouze zkontrolovat, zda je okno aktivní
            if (rootFrame == null)
            {
                // Vytvořit objekt Frame, který bude fungovat jako kontext navigace, a spustit procházení první stránky
                rootFrame = new Frame();

                // TODO: Změňte tuto hodnotu na velikost mezipaměti, která je vhodná pro vaši aplikaci.
                rootFrame.CacheSize = 1;

                if (e.PreviousExecutionState == ApplicationExecutionState.Terminated)
                {
                    // TODO: Načíst stav z dříve pozastavené aplikace
                }

                // Umístit rámec do aktuálního objektu Window
                Window.Current.Content = rootFrame;
            }

            if (rootFrame.Content == null)
            {
                // Odebere navigaci typu Turnstile pro spuštění.
                if (rootFrame.ContentTransitions != null)
                {
                    this.transitions = new TransitionCollection();
                    foreach (var c in rootFrame.ContentTransitions)
                    {
                        this.transitions.Add(c);
                    }
                }

                rootFrame.ContentTransitions = null;
                rootFrame.Navigated += this.RootFrame_FirstNavigated;

                // Není-li navigační zásobník obnoven, navigovat na první stránku
                // a nakonfigurovat novou stránku předáním požadovaných informací ve formě
                // parametru navigace
                if (!rootFrame.Navigate(typeof(MainPage), e.Arguments))
                {
                    throw new Exception("Failed to create initial page");
                }
            }

            // Zkontrolovat, zda je aktuální okno aktivní
            Window.Current.Activate();
        }

        /// <summary>
        /// Obnoví přechod na obsah po spuštění aplikace.
        /// </summary>
        /// <param name="sender">Objekt, kde je připojena obslužná rutina</param>
        /// <param name="e">Podrobnosti o události navigace</param>
        private void RootFrame_FirstNavigated(object sender, NavigationEventArgs e)
        {
            var rootFrame = sender as Frame;
            rootFrame.ContentTransitions = this.transitions ?? new TransitionCollection() { new NavigationThemeTransition() };
            rootFrame.Navigated -= this.RootFrame_FirstNavigated;
        }

        /// <summary>
        /// Vyvoláno při pozastavení provádění aplikace. Stav aplikace je uložen,
        /// aniž by bylo známo, zda bude aplikace ukončena, nebo zda bude obnovena
        /// s neporušeným obsahem paměti.
        /// </summary>
        /// <param name="sender">Zdroj žádosti o pozastavení.</param>
        /// <param name="e">Podrobnosti žádosti o pozastavení.</param>
        private void OnSuspending(object sender, SuspendingEventArgs e)
        {
            var deferral = e.SuspendingOperation.GetDeferral();

            // TODO: Uložit stav aplikace a zastavit jakoukoliv aktivitu na pozadí
            deferral.Complete();
        }
    }
}