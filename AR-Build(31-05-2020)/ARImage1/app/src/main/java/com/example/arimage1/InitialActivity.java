package com.example.arimage1;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.hardware.camera2.CameraManager;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.google.zxing.Result;

import org.jetbrains.annotations.NotNull;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URI;
import java.net.URL;

import me.dm7.barcodescanner.zxing.ZXingScannerView;
import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import pub.devrel.easypermissions.AfterPermissionGranted;
import pub.devrel.easypermissions.EasyPermissions;

import static android.Manifest.permission_group.CAMERA;
import static android.content.Intent.ACTION_VIEW;
public class InitialActivity extends AppCompatActivity implements ZXingScannerView.ResultHandler {
private Button search,resumeQRScan;
private TextView dbResult,resultApi;
    HttpURLConnection urlConnection;
    private static final int REQUEST=123;
     private ZXingScannerView scannerView;
     private OkHttpClient client;
     int STORAGE_PERMISSION_CODE=1;
    public  void downloadFileAsync(final String downloadUrl) throws Exception {
        OkHttpClient client = new OkHttpClient();
        Request request = new Request.Builder().url(downloadUrl).build();
        client.newCall(request).enqueue(new Callback() {
            public void onFailure(Call call, IOException e) {
                resultApi.setText("File Download failed");
                e.printStackTrace();
            }
            public void onResponse(Call call, Response response) throws IOException {
                if (!response.isSuccessful()) {
                    resultApi.setText("Server Response Failed");
                    throw new IOException("Failed to download file: " + response);
                }
                try {
                    String baseDir = Environment.getExternalStorageDirectory().getAbsolutePath();
                    String fileName = "ArDatabase/img.imgdb";
                    File f = new File(baseDir + File.separator + fileName);
                    FileOutputStream fos = new FileOutputStream(f);
                    fos.write(response.body().bytes());
                    fos.close();
                }
                catch (Exception e)
                {
                    resultApi.setText("Andriod File Error!");
                    Log.e("error98765",e.toString());
                }
            }
        });
    }
     public void checkValidId(String link,String id) {
         String url=link+"?id="+id;
         client=new OkHttpClient();
         Request request=new Request.Builder().url(url).build();
         client.newCall(request).enqueue(new Callback() {
             @Override
             public void onFailure(@NotNull Call call, @NotNull IOException e) {
                 resultApi.setText("Cannot Connect to Server\n");
             }

             @Override
             public void onResponse(@NotNull Call call, @NotNull Response response) throws IOException {
                    if(response.isSuccessful()){
                        String res=response.body().string();
                        InitialActivity.this.runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                boolean error=false;
                                if(res.equals("true")) {
                                    resultApi.setText("Database Found Succesfully");
                                    try {
                                        downloadFileAsync("http://192.168.0.108/portal/"+id+"/img.imgdb");
                                    } catch (Exception e) {
                                        resultApi.setText("File Download failed");
                                        error=true;
                                        e.printStackTrace();
                                    }
                                    if(!error) {
                                        Intent intent = new Intent(InitialActivity.this, MainActivity.class);
                                        intent.putExtra("Id", id);
                                        startActivity(intent);
                                    }
                                }
                                else
                                {
                                    resultApi.setText("Database Not Found");
                                }
                  //              Log.i("Res1234",res);
                            }
                        });
                    }
                    else {
                        //Log.i("Fail","12543");
                    }
             }
         });
        //dbResult.setText("0");

     }
    public  void RequestAllPermissions(String[] permissions,int requestCode)
    {
        if(!EasyPermissions.hasPermissions(this,permissions))
        {
            EasyPermissions.requestPermissions(this,"Apps Need this permissions",requestCode,permissions);
        }
    }
     @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_initial);
        search=findViewById(R.id.search);
        resumeQRScan=findViewById(R.id.scanResume);
        resultApi=findViewById(R.id.result);
        scannerView=(ZXingScannerView) findViewById(R.id.zxscan);
        dbResult=findViewById(R.id.DBResult);
        scannerView.setResultHandler(this);
        //External Storage read permission
         RequestAllPermissions(new String[]{Manifest.permission.WRITE_EXTERNAL_STORAGE,Manifest.permission.CAMERA},REQUEST);
        search.setOnClickListener(new View.OnClickListener() {
            //check for storage permission
            @Override
            public void onClick(View v) {
                String result=dbResult.getText().toString();
                if(!result.isEmpty())
                {
                    checkValidId("http://192.168.0.108/portal/api/apiCheckId.php",dbResult.getText().toString());
                }
            }
        });
        resumeQRScan.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                scannerView.resumeCameraPreview(InitialActivity.this);
                resumeQRScan.setVisibility(View.INVISIBLE);
            }
        });

    }

    @Override
    public void onResume() {
        super.onResume();
        scannerView.setResultHandler(this);
        scannerView.startCamera();
    }
    @Override
    public void onDestroy()
    {
        super.onDestroy();
        scannerView.stopCamera();
    }

    public void displayAlertMessage(String message, DialogInterface.OnClickListener listener)
    {
    new AlertDialog.Builder(InitialActivity.this)
            .setMessage(message)
            .setPositiveButton("OK",listener)
            .setNegativeButton("Cancel",null)
            .create()
            .show();
    }
    @Override
    public void handleResult(Result rawResult) {
    String  scanResult=rawResult.getText();
    AlertDialog.Builder builder=new AlertDialog.Builder(this);
    builder.setTitle("Database name");
    builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
           // scannerView.resumeCameraPreview(InitialActivity.this);
        }
    });
    resumeQRScan.setVisibility(View.VISIBLE);
    dbResult.setText(scanResult);
    builder.setMessage(scanResult);
    AlertDialog alert=builder.create();
    alert.show();
    }
}
