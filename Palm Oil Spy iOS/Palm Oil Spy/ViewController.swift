//
//  ViewController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit
import AVFoundation

private extension Selector {
    static let flashlightButtonPressed = #selector(ViewController.setFlashlightButtonIcon)
}

class ViewController: UIViewController, AVCaptureMetadataOutputObjectsDelegate, ApiConnectorDelegate {

    @IBOutlet weak var cameraView: UIView!
    @IBOutlet weak var statusView: UIView!
    @IBOutlet weak var eanCodeLabel: UILabel!
    @IBOutlet weak var resultView: ResultView!
    @IBOutlet weak var soundButton: UIBarButtonItem!
    @IBOutlet weak var flashlightButton: UIBarButtonItem!
    
    var captureSession: AVCaptureSession?
    var videoPreviewLayer: AVCaptureVideoPreviewLayer?
    var device: AVCaptureDevice?
    var flashlight: Flashlight?
    var lastEANcode: String? = ""
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        loadSettings()

        NSNotificationCenter.defaultCenter().addObserver(self,
            selector: .flashlightButtonPressed,
            name: UIApplicationDidBecomeActiveNotification,
            object: nil)
    }
    
    
    deinit {
        NSNotificationCenter.defaultCenter().removeObserver(self)
    }
    
    
    override func preferredStatusBarStyle() -> UIStatusBarStyle {
        return UIStatusBarStyle.LightContent
    }
    
    
    override func viewDidAppear(animated: Bool) {
        super.viewDidAppear(animated)
        
        initCamera()
    }
    
    
    override func viewDidDisappear(animated: Bool) {
        super.viewDidDisappear(animated)
        
        captureSession?.stopRunning()
        captureSession = nil
        videoPreviewLayer?.removeFromSuperlayer()
    }
    
    
    func loadSettings() {
        guard let settings = appDelegate.settings else {return}
        
        resultView.settings = settings
        
        switch settings.soundsEnabled {
        case true:
            soundButton.image = UIImage(named: "icon-sound")
        case false:
            soundButton.image = UIImage(named: "icon-sound-selected")
        }
        
        if settings.firstRun {
            settings.firstRun = false
            settings.saveSettings()
            performSegueWithIdentifier("segueToInfo", sender: self)
        }
    }


// MARK: camera stuff
    
    
    func initCamera() {
        captureSession = AVCaptureSession()
        device = AVCaptureDevice.defaultDeviceWithMediaType(AVMediaTypeVideo)
        
        if let device = device {
            flashlight = Flashlight(device: device)
        }

        do {
            let input = try AVCaptureDeviceInput(device: device)
            captureSession?.addInput(input)
            let captureMetadataOutput = AVCaptureMetadataOutput()
            captureSession?.addOutput(captureMetadataOutput)
            let camQueue: dispatch_queue_t = dispatch_queue_create("palmOilCam", nil)
            captureMetadataOutput.setMetadataObjectsDelegate(self, queue: camQueue)
            captureMetadataOutput.metadataObjectTypes = [AVMetadataObjectTypeEAN8Code, AVMetadataObjectTypeEAN13Code, AVMetadataObjectTypeQRCode]
            connectCameraView()
        }
        catch {
            print("Camera init problem")
        }
    }
    
    
    func connectCameraView() {
        guard let videoPreviewLayer = AVCaptureVideoPreviewLayer(session: captureSession) else { return }
        videoPreviewLayer.videoGravity = AVLayerVideoGravityResizeAspectFill
        videoPreviewLayer.frame = cameraView.layer.bounds
        cameraView.layer.addSublayer(videoPreviewLayer)
        captureSession?.startRunning()
    }

    
    func captureOutput(captureOutput: AVCaptureOutput!, didOutputMetadataObjects metadataObjects: [AnyObject]!, fromConnection connection: AVCaptureConnection!) {
        guard let metadata = metadataObjects where metadataObjects.count > 0 else { return }
        guard let code = metadata[0] as? AVMetadataMachineReadableCodeObject else { return }
        
        switch code.type {
        case AVMetadataObjectTypeQRCode:
            print("Code is! : \(code)")
        case AVMetadataObjectTypeEAN13Code:
            dispatch_async(dispatch_get_main_queue(), {
                if self.lastEANcode != code.stringValue || self.resultView.oilStatus == .None {
                    self.lastEANcode = code.stringValue
                    print("EAN13 code detected : \(self.lastEANcode!)")
                    self.identifyCode(code.stringValue)
                }
            })
        case AVMetadataObjectTypeEAN8Code:
            dispatch_async(dispatch_get_main_queue(), {
                if self.lastEANcode != code.stringValue || self.resultView.oilStatus == .None  {
                    self.lastEANcode = code.stringValue
                    print("EAN8 code detected : \(self.lastEANcode!)")
                    self.identifyCode(code.stringValue)
                }
            })
        default:
            break;
        }
    }
    
    
    func identifyCode(code: String) {
//        let connector = ApiConnector()
//        connector.delegate = self
//        connector.eanCodeIdentify(code)
        
//         FIXME: Demo hack
        dispatch_async(dispatch_get_main_queue(), {
            if code == "3045140105502" {
                self.resultView.oilStatus = .Bad
            }
            else {
                self.resultView.oilStatus = .Good
            }
        })
    }
    
    
    // MARK: buttons
    
    
    @IBAction func flashButtonPressed(sender: UIBarButtonItem) {
        guard let flashlight = flashlight else {return}
        
        switch flashlight.device.torchActive {
        case false:
            flashlight.turnOn()
        case true:
            flashlight.turnOff()
        }
        
        setFlashlightButtonIcon()
    }
    
    
    func setFlashlightButtonIcon() {
        guard let flashlight = flashlight else {return}
        
        switch flashlight.device.torchMode {
        case .On:
            flashlightButton.image = UIImage(named: "icon-flash-selected")
        case .Off:
            flashlightButton.image = UIImage(named: "icon-flash")
        case .Auto:
            flashlightButton.image = UIImage(named: "icon-flash")
        }
    }
    
    
    @IBAction func homeButtonPressed(sender: UIBarButtonItem) {
    }
    
    
    @IBAction func soundButtonPressed(sender: UIBarButtonItem) {
        guard let settings = appDelegate.settings else {return}
        
        settings.soundsEnabled = !settings.soundsEnabled
        settings.saveSettings()
        switch settings.soundsEnabled {
        case true:
            sender.image = UIImage(named: "icon-sound")
        case false:
            sender.image = UIImage(named: "icon-sound-selected")
        }
    }

    
// MARK: ApiConnectorDelegate
    
    
    func didRecieveAnswer(answer: OilResults) {
        dispatch_async(dispatch_get_main_queue(), {
            self.resultView.oilStatus = answer
        })
    }
    
    
    override func prepareForSegue(segue: UIStoryboardSegue, sender: AnyObject?) {
        segue.destinationViewController.modalPresentationStyle = .Custom
        segue.destinationViewController.transitioningDelegate = segue.destinationViewController as? UIViewControllerTransitioningDelegate
    }
}

