//
//  ViewController.swift
//  Palm Oil Spy
//
//  Created by Vladimír Bělohradský on 11/04/15.
//  Copyright (c) 2015 GDGSCL. All rights reserved.
//

import UIKit
import AVFoundation

class ViewController: UIViewController, AVCaptureMetadataOutputObjectsDelegate, ApiConnectorDelegate {

    @IBOutlet weak var cameraView: UIView!
    @IBOutlet weak var statusView: UIView!
    @IBOutlet weak var eanCodeLabel: UILabel!
    @IBOutlet weak var resultView: ResultView!
    @IBOutlet weak var flashButton: FlashButton!
    
    var captureSession: AVCaptureSession?
    var videoPreviewLayer: AVCaptureVideoPreviewLayer?
    var device: AVCaptureDevice?
    
    var lastEANcode: String? = ""
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
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


    // MARK: camera stuff
    
    
    func initCamera() {
        captureSession = AVCaptureSession()
        device = AVCaptureDevice.defaultDeviceWithMediaType(AVMediaTypeVideo)
        var error: NSError?
        if let input = AVCaptureDeviceInput.deviceInputWithDevice(device!, error: &error) as? AVCaptureDeviceInput {
            
            captureSession?.addInput(input)
            var captureMetadataOutput = AVCaptureMetadataOutput()
            captureSession?.addOutput(captureMetadataOutput)
            var camQueue: dispatch_queue_t = dispatch_queue_create("palmOilCam", nil)
            captureMetadataOutput.setMetadataObjectsDelegate(self, queue: camQueue)
            captureMetadataOutput.metadataObjectTypes = [AVMetadataObjectTypeEAN8Code, AVMetadataObjectTypeEAN13Code, AVMetadataObjectTypeQRCode]
            
            connectCameraView()
        }
        else {
            println("Camera init problem: \(error?.localizedDescription)")
        }
    }
    
    func connectCameraView() {
        videoPreviewLayer = AVCaptureVideoPreviewLayer(session: captureSession)
        videoPreviewLayer?.videoGravity = AVLayerVideoGravityResizeAspectFill
        videoPreviewLayer?.frame = cameraView.layer.bounds
        cameraView.layer.addSublayer(videoPreviewLayer)
        captureSession?.startRunning()
    }
    
    func captureOutput(captureOutput: AVCaptureOutput!, didOutputMetadataObjects metadataObjects: [AnyObject]!, fromConnection connection: AVCaptureConnection!) {
        if let metadata = metadataObjects where metadataObjects.count > 0 {
            
            var code = metadata[0] as? AVMetadataMachineReadableCodeObject

            if code?.type == AVMetadataObjectTypeQRCode {
                println("Code is! : \(code)")
            }
            
            if code?.type == AVMetadataObjectTypeEAN13Code {
                dispatch_async(dispatch_get_main_queue(), { () -> Void in
                    if self.lastEANcode != code?.stringValue {
//                        self.eanCodeLabel.text = code?.stringValue
                        self.lastEANcode = code?.stringValue
                        println("EAN13 code detected! : \(self.lastEANcode)")
                        self.identifyCode(code!.stringValue)
                    }
                })
            }
            
            if code?.type == AVMetadataObjectTypeEAN8Code {
                dispatch_async(dispatch_get_main_queue(), { () -> Void in
                    if self.lastEANcode != code?.stringValue {
//                        self.eanCodeLabel.text = code?.stringValue
                        self.lastEANcode = code?.stringValue
                        println("EAN8 code detected! : \(self.lastEANcode)")
                        self.identifyCode(code!.stringValue)
                    }
                })
            }
        }
    }
    
    func identifyCode(code: String) {
        let connector = ApiConnector()
        connector.delegate = self
        connector.eanCodeIdentify(code)
        
//        dispatch_async(dispatch_get_main_queue(), { () -> Void in
//
//            if code == "50112128" {
//                self.resultView.oilStatus = OilResults.Good
//            }
//            else {
//                self.resultView.oilStatus = OilResults.Bad
//            }
//        })
    }
    
    // MARK: buttons
    
    
    @IBAction func flashButtonPressed(sender: AnyObject) {
        
        if let device = self.device where device.hasTorch {
            device.lockForConfiguration(nil)
            device.torchMode = device.torchActive ? .Off : .On
            device.unlockForConfiguration()
        }
    }
    
    // MARK: ApiConnectorDelegate
    
    func didRecieveAnswer(answer: OilResults) {
        dispatch_async(dispatch_get_main_queue(), { () -> Void in
            self.resultView.oilStatus = answer
        })
    }
}

