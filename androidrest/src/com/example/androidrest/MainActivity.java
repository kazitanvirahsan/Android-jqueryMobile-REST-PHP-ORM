package com.example.androidrest;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.net.URL;

import android.app.Activity;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.webkit.ConsoleMessage;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

public class MainActivity extends Activity {
    
	private WebView browser;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		browser = (WebView) findViewById(R.id.webview);
		// create custom browser within application
		browser.setWebViewClient(new MyBrowser());
		browser.setWebChromeClient(new DebugWebChromeClient()); 
		//clear cache
		browser.clearCache(true);
		// enable javascript
		WebSettings webSettings = browser.getSettings();
		webSettings.setJavaScriptEnabled(true);
		
		if(Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN){
			//browser.setAllowUniversalAccessFromFileURLs(true);
			//browser.setAllowFileAccessFromFileURLs(true);
	    }
		
		browser.loadUrl("file:///android_asset/www/index.html");
		//browser.loadUrl("http://10.0.0.3/");
		//browser.loadUrl("http://10.0.0.3/");
		//browser.loadUrl("javascript:(function(){ alert('I am JS'); })();");
		//String html = readHtml("http://10.0.0.3/");
		//browser.loadDataWithBaseURL("file:///android_asset/", html, "text/html", "utf-8", "");
	}
	
	
	private class DebugWebChromeClient extends WebChromeClient {
	    private static final String TAG = "DebugWebChromeClient";

	    @Override
	    public boolean onConsoleMessage(ConsoleMessage m) {
	            Log.d(TAG, m.lineNumber() + ": " + m.message() + " - " + m.sourceId());

	            return true;
	    }

	}
	
	 private class MyBrowser extends WebViewClient {
	      @Override
	      public boolean shouldOverrideUrlLoading(WebView view, String url) {
	         view.loadUrl(url);
	         return true;
	      }
	   }
	
	 
	 private String readHtml(String remoteUrl) {
		    String out = "";
		    BufferedReader in = null;
		    try {
		        URL url = new URL(remoteUrl);
		        in = new BufferedReader(new InputStreamReader(url.openStream()));
		        String str;
		        while ((str = in.readLine()) != null) {
		            out += str;
		        }
		    } catch (MalformedURLException e) { 
		    } catch (IOException e) { 
		    } finally {
		        if (in != null) {
		            try {
		                in.close();
		            } catch (IOException e) {
		                e.printStackTrace();
		            }
		        }
		    }
		    return out;
		} 
	 
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}

}
