import os
import sys
import json
from openai import OpenAI

# API-Key aus Umgebungsvariable laden
api_key = os.getenv("OPENAI_API_KEY")
if not api_key:
    print(json.dumps({"error": "OPENAI_API_KEY Umgebungsvariable nicht gesetzt."}), file=sys.stderr)
    sys.exit(1)

client = OpenAI(api_key=api_key)

'''
      const response = await openai.responses.create({
          model: "gpt-4.1-mini",
          input: chatgtp_input.value,
      });
'''
def generate_subtasks(main_task: str):
    try:
        response = client.chat.completions.create(
            model="gpt-4.1-mini",
            messages=[
                {"role": "system", "content": "Du bist ein hilfreicher Assistent, der komplexe Aufgaben in kleinere, umsetzbare Unteraufgaben zerlegt. Jede Unteraufgabe sollte einen Titel haben. Antworte ausschließlich im JSON-Format, wie im Beispiel gezeigt."},
                {"role": "user", "content": f"""Zerlege die folgende Aufgabe in Unteraufgaben und gebe als Antwort ein JSON im folgenden Format aus {{"subtasks": [{{"title": "Titel der Unteraufgabe", "points_upon_completion": "Je nach Größe der Unteraufgabe anteilige Punkte (ein vielfaches von 5)"}}, ...]}}:
                {main_task}"""}
            ],
            response_format={"type": "json_object"} # Wichtig für die Erzwingung des JSON-Formats
        )
        
        # Die Antwort des Modells ist ein String, der JSON enthält
        json_output = response.choices[0].message.content

        return json.loads(json_output)
        
    except Exception as e:
        # Hier solltest du robustere Fehlerbehandlung implementieren
        print(json.dumps({"error": str(e)}), file=sys.stderr)
        sys.exit(1) # Beende das Skript mit einem Fehlercode

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "Fehlende Hauptaufgabe als Argument."}), file=sys.stderr)
        sys.exit(1)
    
    main_task = json.loads(sys.argv[1])
    
    result = generate_subtasks(main_task)
    print(json.dumps(result)) # Gib das Ergebnis als JSON aus