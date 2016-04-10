package com.google.zxing.client.android;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.os.Bundle;
import android.text.Html;
import android.view.LayoutInflater;
import android.webkit.WebView;
import android.widget.TextView;

public class AboutDialogFragment extends DialogFragment {
    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {
        // Use the Builder class for convenient dialog construction
        AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());

        final WebView message = new WebView(this.getActivity());

        //message.loadDataWithBaseURL(null, getString(R.string.about_content), "text/html", "utf-8", null);
        message.loadUrl("file:///android_asset/html/about.html");

        builder.setView(message)
                // Add action buttons
                .setPositiveButton(R.string.button_close, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int id) {
                        AboutDialogFragment.this.getDialog().cancel();
                    }
                });
        // Create the AlertDialog object and return it
        return builder.create();
    }
}