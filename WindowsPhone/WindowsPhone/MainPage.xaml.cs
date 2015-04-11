using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Runtime.InteropServices.WindowsRuntime;
using Windows.Foundation;
using Windows.Foundation.Collections;
using Windows.UI.Xaml;
using Windows.UI.Xaml.Controls;
using Windows.UI.Xaml.Controls.Primitives;
using Windows.UI.Xaml.Data;
using Windows.UI.Xaml.Input;
using Windows.UI.Xaml.Media;
using Windows.UI.Xaml.Navigation;

// Dokumentaci k šabloně položky prázdné stránky najdete na adrese http://go.microsoft.com/fwlink/?LinkId=391641.

namespace WindowsPhone
{
    /// <summary>
    /// Prázdné stránka, která může být použita samostatně, nebo v rámci prvku Frame.
    /// </summary>
    public sealed partial class MainPage : Page
    {
        public MainPage()
        {
            this.InitializeComponent();

            this.NavigationCacheMode = NavigationCacheMode.Required;
        }

        /// <summary>
        /// Vyvoláno, když má být tato stránka zobrazena v rámci.
        /// </summary>
        /// <param name="e">Data události popisující, jak bylo této stránky dosaženo.
        /// Tento parametr se obvykle používá pro konfiguraci stránky.</param>
        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
            // TODO: Připravit stránku, aby se zde zobrazila

            // TODO: Pokud má vaše aplikace více stránek, zajistěte, že bude reagovat
            // na hardwarové tlačítko Zpět, tak, že zaregistrujete zpracování
            // události Windows.Phone.UI.Input.HardwareButtons.BackPressed.
            // Pokud používáte objekt NavigationHelper, který poskytují některé šablony,
            // je pro vás tato událost zpracována.
        }
    }
}
