using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Navigation;
using Microsoft.Phone.Controls;
using Microsoft.Phone.Shell;
using WindowsPhone.Resources;
using Microsoft.Devices;
using System.IO;
using System.IO.IsolatedStorage;
using Microsoft.Xna.Framework.Media;
using ZXing;
using System.Windows.Threading;
using System.Windows.Media.Imaging;
using System.Windows.Input;

namespace WindowsPhone
{
    public partial class MainPage : PhoneApplicationPage
    {

        private PhotoCamera _phoneCamera;
        private IBarcodeReader _barcodeReader;
        private DispatcherTimer _scanTimer;
        private WriteableBitmap _previewBuffer;

        // Constructor
        public MainPage()
        {
            InitializeComponent();

            // Sample code to localize the ApplicationBar
            //BuildLocalizedApplicationBar();
        }

        protected override void OnNavigatedTo(NavigationEventArgs e)
        {
            _phoneCamera = new PhotoCamera();
            _phoneCamera.Initialized += cam_Initialized;
            _phoneCamera.AutoFocusCompleted += _phoneCamera_AutoFocusCompleted;
            CameraButtons.ShutterKeyHalfPressed += CameraButtons_ShutterKeyHalfPressed;

            viewfinderBrush.SetSource(_phoneCamera);

            _scanTimer = new DispatcherTimer();
            _scanTimer.Interval = TimeSpan.FromMilliseconds(250);
            _scanTimer.Tick += (o, arg) => ScanForBarcode();

            viewfinderCanvas.Tap += new EventHandler<GestureEventArgs>(focus_Tapped);

            base.OnNavigatedTo(e);
        }

        void _phoneCamera_AutoFocusCompleted(object sender, CameraOperationCompletedEventArgs e)
        {
            Deployment.Current.Dispatcher.BeginInvoke(delegate()
            {
                focusBrackets.Visibility = Visibility.Collapsed;
            });
        }

        void focus_Tapped(object sender, GestureEventArgs e)
        {
            if (_phoneCamera != null)
            {
                if (_phoneCamera.IsFocusAtPointSupported == true)
                {
                    Point tapLocation = e.GetPosition(viewfinderCanvas);
                    focusBrackets.SetValue(Canvas.LeftProperty, tapLocation.X - 30);
                    focusBrackets.SetValue(Canvas.TopProperty, tapLocation.Y - 28);

                    double focusXPercentage = tapLocation.X / viewfinderCanvas.ActualWidth;
                    double focusYPercentage = tapLocation.Y / viewfinderCanvas.ActualHeight;

                    focusBrackets.Visibility = Visibility.Visible;
                    _phoneCamera.FocusAtPoint(focusXPercentage, focusYPercentage);
                }
            }
        }

        void CameraButtons_ShutterKeyHalfPressed(object sender, EventArgs e)
        {
            _phoneCamera.Focus();
        }

        protected override void onNavigatingFrom(NavigatingCancelEventArgs e)
        {
            _scanTimer.Stop();

            if (_phoneCamera != null)
            {
                _phoneCamera.Dispose();
                _phoneCamera.Initialized -= cam_Initialized;
                CameraButtons.ShutterKeyHalfPressed -= CameraButtons_ShutterKeyHalfPressed;
            }
        }

        void cam_Initialized(object sender, CameraOperationCompletedEventArgs e)
        {
            if (e.Succeeded)
            {
                this.Dispatcher.BeginInvoke(delegate()
                {
                    _phoneCamera.FlashMode = FlashMode.Off;
                    _previewBuffer = new WriteableBitmap((int)_phoneCamera.PreviewResolution.Width, (int)_phoneCamera.PreviewResolution.Height);

                    _barcodeReader = new BarcodeReader();
                    var SupportedBarcodeFormats = new List<BarcodeFormat>();
                    SupportedBarcodeFormats.Add(BarcodeFormat.EAN_13);
                    SupportedBarcodeFormats.Add(BarcodeFormat.EAN_8);
                    _barcodeReader.Options.PossibleFormats = SupportedBarcodeFormats;

                    _barcodeReader.Options.TryHarder = true;

                    _barcodeReader.ResultFound +=_barcodeReader_ResultFound;
                    _scanTimer.Start();
                });
            }
            else
            {
                Dispatcher.BeginInvoke(() =>
                {
                    MessageBox.Show("Nebylo možné spustit fotoaparát.");
                });
            }
        }

        void _barcodeReader_ResultFound(Result obj)
        {
            if (!obj.Text.Equals(tbBarcodeData.Text))
            {
                VibrateController.Default.Start(TimeSpan.FromMilliseconds(100));
                tbBarcodeType.Text = obj.BarcodeFormat.ToString();
                tbBarcodeData.Text = obj.Text;
            }
        }

        private void ScanForBarcode()
        {
            _phoneCamera.GetPreviewBufferArgb32(_previewBuffer.Pixels);
            _previewBuffer.Invalidate();

            _barcodeReader.Decode(_previewBuffer);
        }

        // Sample code for building a localized ApplicationBar
        //private void BuildLocalizedApplicationBar()
        //{
        //    // Set the page's ApplicationBar to a new instance of ApplicationBar.
        //    ApplicationBar = new ApplicationBar();

        //    // Create a new button and set the text value to the localized string from AppResources.
        //    ApplicationBarIconButton appBarButton = new ApplicationBarIconButton(new Uri("/Assets/AppBar/appbar.add.rest.png", UriKind.Relative));
        //    appBarButton.Text = AppResources.AppBarButtonText;
        //    ApplicationBar.Buttons.Add(appBarButton);

        //    // Create a new menu item with the localized string from AppResources.
        //    ApplicationBarMenuItem appBarMenuItem = new ApplicationBarMenuItem(AppResources.AppBarMenuItemText);
        //    ApplicationBar.MenuItems.Add(appBarMenuItem);
        //}
    }
}