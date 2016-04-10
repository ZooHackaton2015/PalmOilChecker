package com.google.zxing.client.android;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.Charset;

import org.apache.http.Header;
import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.HttpStatus;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

public class ApiClient {
	
	private static final String BASE_URL = "http://palmoil-zoohackaton.rhcloud.com/";
	
	public BarCodeInfo getInfo(String barcode) {
		HttpGet get = new HttpGet(BASE_URL + "barcodes/" + barcode);
		HttpClient client = new DefaultHttpClient();
		HttpResponse resp;
		try {
			resp = client.execute(get);
		} catch (IOException e1) {
			return null;
		}
		if(resp.getStatusLine().getStatusCode() != HttpStatus.SC_OK) {
			return null;
		}
		BarCodeInfo info = new BarCodeInfo();

		try {
			JSONObject json = new JSONObject(this.readResponse(resp.getEntity()));
			info.setContainsOil(json.getBoolean("contains-oil"));
		} catch (JSONException | IOException e) {
			return null;
		}
		return info;
	}

	private String readResponse(HttpEntity ent) throws IOException {
		Header enc = ent.getContentEncoding();
		String ch;
		if(enc == null) {
			ch = "UTF-8";
		} else {
			ch = enc.getValue();
		}
		InputStream is = ent.getContent();
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		int x;
		while((x = is.read()) >= 0) {
			baos.write(x);
		}
		return new String(baos.toByteArray(), Charset.forName(ch));
	}


}
