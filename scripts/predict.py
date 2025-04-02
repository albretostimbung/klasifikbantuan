import pymysql
import pandas as pd
import json
import os
from dotenv import load_dotenv
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import accuracy_score, classification_report, confusion_matrix

# Load environment variables from Laravel .env
if (load_dotenv("/www/wwwroot/klasifikbantuan/.env")):
    load_dotenv("/www/wwwroot/klasifikbantuan/.env")
    # 📌 Koneksi ke Database MySQL
    db_connection = pymysql.connect(
        host=os.getenv("DB_HOST"),
        user=os.getenv("DB_USERNAME"),
        password=os.getenv("DB_PASSWORD"),
        database=os.getenv("DB_DATABASE")
    )
else:
    load_dotenv("/var/www/.env")
    # 📌 Koneksi ke Database MySQL
    db_connection = pymysql.connect(
        host=os.getenv("DB_HOST"),
        user=os.getenv("DB_USERNAME"),
        password=os.getenv("DB_PASSWORD"),
        database=os.getenv("DB_DATABASE")
    )
cursor = db_connection.cursor()

# 📌 Ambil data dari citizens & attribute_citizens
query = """
    SELECT c.id AS citizen_id, c.age, c.income, c.occupation, c.number_of_dependent,
           c.residence_status, c.last_education, c.marital_status,
           ac.attribute_id, ac.value
    FROM citizens c
    LEFT JOIN attribute_citizens ac ON c.id = ac.citizen_id
"""
df = pd.read_sql(query, db_connection)

# 📌 Ambil nama atribut dari tabel `attributes`
attribute_query = "SELECT id, name FROM attributes"
attributes = pd.read_sql(attribute_query, db_connection).set_index("id")["name"].to_dict()

# 📌 Pivot attribute_citizens agar menjadi kolom baru
df_pivot = df.pivot(index='citizen_id', columns='attribute_id', values='value')
df_pivot.rename(columns=attributes, inplace=True)

# 📌 Gabungkan dengan data citizens
df_main = df.drop(columns=['attribute_id', 'value']).drop_duplicates().set_index('citizen_id')
df_final = df_main.join(df_pivot).reset_index()

# 📌 Tangani missing values
df_final.fillna("Unknown", inplace=True)

# 📌 Pastikan ada kolom "Bantuan Sosial Sebelumnya"
if 'Bantuan Sosial Sebelumnya' not in df_final.columns:
    raise ValueError("Kolom 'Bantuan Sosial Sebelumnya' tidak ditemukan!")

# 📌 Pisahkan Fitur & Label
X = df_final.drop(columns=['citizen_id', 'Bantuan Sosial Sebelumnya'])
y = df_final['Bantuan Sosial Sebelumnya']

# 📌 Label Encoding untuk kolom kategorikal
label_encoders = {}
for col in X.select_dtypes(include=['object']).columns:
    le = LabelEncoder()
    X[col] = le.fit_transform(X[col])
    label_encoders[col] = le

# 📌 Bootstrapping untuk meningkatkan generalisasi
df_bootstrap = X.sample(frac=1, replace=True, random_state=42)
y_bootstrap = y.loc[df_bootstrap.index]

# 📌 Split Data: 80% Training, 20% Testing
X_train, X_test, y_train, y_test = train_test_split(
    df_bootstrap, y_bootstrap, test_size=0.2, stratify=y_bootstrap, random_state=42
)

# 📌 create sequence and make sure not duplicate
cursor.execute("SELECT MAX(name) FROM model_evaluations")
result = cursor.fetchone()
latest_sequence = result[0] if result else None

sequence = int(latest_sequence.split(" ")[-1]) + 1 if latest_sequence else 1

# 📌 Tambahkan data model ke tabel model_evaluations
id = cursor.execute(
    "INSERT INTO model_evaluations (name, created_at) VALUES (%s, NOW())",
    ("Decision Tree C4.5 - " + str(sequence))
)

db_connection.commit()

# 📌 Dapatkan ID terakhir yang di-insert
cursor.execute("SELECT LAST_INSERT_ID()")
model_evaluation_id = cursor.fetchone()[0]

# 📌 Buat Model Decision Tree C4.5
dt_model = DecisionTreeClassifier(
    criterion="entropy",
    max_depth=30,        # Batasi kedalaman
    ccp_alpha=0.005      # Pruning untuk menghindari overfitting
)
dt_model.fit(X_train, y_train)

# 📌 Prediksi pada Data Testing
y_pred = dt_model.predict(X_test)

# 📌 Evaluasi Model
accuracy = accuracy_score(y_test, y_pred)
classification_report_result = classification_report(y_test, y_pred, output_dict=True)
conf_matrix = confusion_matrix(y_test, y_pred)

# 📌 Simpan hasil prediksi ke dalam DataFrame
df_hasil = pd.DataFrame({'citizen_id': X_test.index, 'is_eligible': y_pred})

# 📌 Ambil daftar citizen_id yang valid dari database
cursor.execute("SELECT id FROM citizens")
valid_citizen_ids = {row[0] for row in cursor.fetchall()}

# 📌 Simpan hasil prediksi ke dalam tabel predicts
total_saved = 0
for _, row in df_hasil.iterrows():
    if row['citizen_id'] in valid_citizen_ids:
        cursor.execute(
            "INSERT INTO predicts (model_evaluation_id, citizen_id, is_eligible, created_at, updated_at) VALUES (%s, %s, %s, NOW(), NOW())",
            (model_evaluation_id, row['citizen_id'], 1 if row['is_eligible'] == 'Ya' else 0)
        )
        total_saved += 1

db_connection.commit()

# 📌 Update model_evaluations dengan hasil evaluasi
cursor.execute(
    "UPDATE model_evaluations SET accuracy = %s, conf_matrix = %s, model_precision = %s, model_recall = %s, model_f1_score = %s WHERE name = %s",
    (accuracy, json.dumps(conf_matrix.tolist()), classification_report_result["weighted avg"]["precision"], classification_report_result["weighted avg"]["recall"], classification_report_result["weighted avg"]["f1-score"], 'Decision Tree C4.5 - ' + str(sequence))
)

db_connection.commit()

# 📌 Simpan hasil dalam JSON
result_json = json.dumps({
    "accuracy": accuracy,
    "conf_matrix": conf_matrix.tolist(),
    "precision": classification_report_result["weighted avg"]["precision"],
    "recall": classification_report_result["weighted avg"]["recall"],
    "f1_score": classification_report_result["weighted avg"]["f1-score"]
}, indent=4)

# 📌 Print hasil JSON
print(result_json)

# 📌 Tutup koneksi database
cursor.close()
db_connection.close()