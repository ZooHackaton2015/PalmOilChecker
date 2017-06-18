//
//  ViewController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit
import AVFoundation

fileprivate extension Selector {
    static let flashlightButtonPressed = #selector(ScannerViewController.flashButtonPressed(_:))
}

class ScannerViewController: UIViewController, AVCaptureMetadataOutputObjectsDelegate, ApiConnectorDelegate {

    @IBOutlet weak var cameraView: UIView!
    @IBOutlet weak var eanCodeLabel: UILabel!
    @IBOutlet weak var resultView: ResultView!
    @IBOutlet weak var soundButton: UIBarButtonItem!
    @IBOutlet weak var flashlightButton: UIBarButtonItem!
    @IBOutlet var helpButton: UIBarButtonItem!
    
    
    /// Session that handle camera.
    var captureSession: AVCaptureSession?
    
    /// Layer to show image from camera.
    var videoPreviewLayer: AVCaptureVideoPreviewLayer?
    
    /// Current device.
    var device: AVCaptureDevice?
    
    /// Device flashlight.
    var flashlight: Flashlight?
    
    /// Last code memory.
    var lastEANcode: String? = ""
    
    /// Network connection.
    let connector = ApiConnector()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        loadSettings()
        
        connector.delegate = self

        NotificationCenter.default.addObserver(
            self,
            selector: .flashlightButtonPressed,
            name: .UIApplicationDidBecomeActive,
            object: nil
        )
        
        updateSoundButtonAppearance()
        flashlightButton.image = PalmOilGlyphs.imageOfFlashlightOff()
        setAppearance()
    }
    
    
    func setAppearance() {
        helpButton.image = PalmOilGlyphs.imageOfInformation()
    }
    
    
    deinit {
        NotificationCenter.default.removeObserver(self)
    }
    
    
    override var preferredStatusBarStyle : UIStatusBarStyle {
        return .lightContent
    }
    
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
        initCamera()
    }
    
    
    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        
        suspendCamera()
    }
    
    
    //
    // MARK: - Helpers
    //
    
    
    func loadSettings() {
        guard let settings = appDelegate.settings else {return}
        
        resultView.settings = settings
        
        switch settings.soundsEnabled {
        case true:
            soundButton.image = UIImage(named: "icon-sound")
        case false:
            soundButton.image = UIImage(named: "icon-sound-selected")
        }
        
        if settings.isFirstRun {
            settings.isFirstRun = false
            performSegue(withIdentifier: "segueToInfo", sender: self)
        }
    }

    
    //
    // MARK: - Camera stuff
    //
    
    
    /// Start scanner camera.
    func initCamera() {
        captureSession = AVCaptureSession()
        device = AVCaptureDevice.defaultDevice(withMediaType: AVMediaTypeVideo)
        
        if let device = device {
            flashlight = Flashlight(device: device)
        }

        do {
            let input = try AVCaptureDeviceInput(device: device)
            captureSession?.addInput(input)
            let captureMetadataOutput = AVCaptureMetadataOutput()
            captureSession?.addOutput(captureMetadataOutput)
            let camQueue: DispatchQueue = DispatchQueue(label: "palmOilCam", attributes: [])
            captureMetadataOutput.setMetadataObjectsDelegate(self, queue: camQueue)
            captureMetadataOutput.metadataObjectTypes = [
                AVMetadataObjectTypeEAN8Code,
                AVMetadataObjectTypeEAN13Code
            ]
            connectCameraView()
        }
        catch {
            print("Camera init problem")
        }
    }
    
    
    /// Suspend scanner camera.
    func suspendCamera() {
        captureSession?.stopRunning()
        captureSession = nil
        videoPreviewLayer?.removeFromSuperlayer()
    }
    
    
    func connectCameraView() {
        guard let videoPreviewLayer = AVCaptureVideoPreviewLayer(session: captureSession) else { return }
        
        videoPreviewLayer.videoGravity = AVLayerVideoGravityResizeAspectFill
        videoPreviewLayer.frame = cameraView.layer.bounds
        cameraView.layer.addSublayer(videoPreviewLayer)
        captureSession?.startRunning()
    }

    
    func captureOutput(
        _ captureOutput: AVCaptureOutput!,
        didOutputMetadataObjects metadataObjects: [Any]!,
        from connection: AVCaptureConnection!
    ) {
        guard let metadata = metadataObjects , metadataObjects.count > 0,
            let code = metadata[0] as? AVMetadataMachineReadableCodeObject
            else { return }
        
        switch code.type {
        case AVMetadataObjectTypeEAN13Code:
            fallthrough
        case AVMetadataObjectTypeEAN8Code:
            DispatchQueue.main.async(execute: {
                if self.lastEANcode != code.stringValue || self.resultView.oilStatus == .none  {
                    self.lastEANcode = code.stringValue
                    print("EAN code detected : \(self.lastEANcode!)")
                    self.identifyCode(code.stringValue)
                }
            })
        default:
            break;
        }
    }
    
    
    func identifyCode(_ code: String) {
        // Demo hack
        if isDemoActive {
            DispatchQueue.main.async(execute: {
                switch code {
                case "4008381178000":
                    self.resultView.oilStatus = .bad
                case "85909311":
                    self.resultView.oilStatus = .good
                default:
                    self.resultView.oilStatus = .unknow
                }
            })
            return
        }
        
        
        connector.eanCodeIdentify(code)
    }
    
    
    //
    // MARK: - Actions
    //
    
    
    @IBAction func flashButtonPressed(_ sender: UIBarButtonItem) {
        guard let flashlight = flashlight else { return }
        
        switch flashlight.device.isTorchActive {
        case false:
            flashlight.turnOn()
        case true:
            flashlight.turnOff()
        }
        
        updateFlashlightButtonIcon(flashlight: flashlight)
    }
    
    
    func updateFlashlightButtonIcon(flashlight: Flashlight) {
        switch flashlight.device.torchMode {
        case .on:
            flashlightButton.image = PalmOilGlyphs.imageOfFlashlightOn()
        case .off:
            flashlightButton.image = PalmOilGlyphs.imageOfFlashlightOff()
        case .auto:
            flashlightButton.image = PalmOilGlyphs.imageOfFlashlightOff()
        }
    }
    
    
    @IBAction func homeButtonPressed(_ sender: UIBarButtonItem) {}
    
    
    @IBAction func soundButtonPressed(_ sender: UIBarButtonItem) {
        guard let settings = appDelegate.settings else { return }
        
        settings.soundsEnabled = !settings.soundsEnabled
        updateSoundButtonAppearance()
    }
    
    
    func updateSoundButtonAppearance() {
        guard let settings = appDelegate.settings else { return }
        
        switch settings.soundsEnabled {
        case true:
            soundButton.image = PalmOilGlyphs.imageOfSoundsOff()
        case false:
            soundButton.image = PalmOilGlyphs.imageOfSoundsOff()
        }
    }

    
    //
    // MARK: - ApiConnectorDelegate
    //
    
    
    func didRecieveAnswer(_ answer: OilCheckResult) {
        DispatchQueue.main.async { [weak self] in
            self?.resultView.oilStatus = answer
        }
    }
    
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        segue.destination.modalPresentationStyle = .custom
        segue.destination.transitioningDelegate = segue.destination as? UIViewControllerTransitioningDelegate
    }
    
}

